<?php

declare(strict_types=1);

namespace professionalweb\IntegrationHub\IntegrationHubDB\Abstractions;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator as BasePaginator;
use Illuminate\Contracts\Database\Query\Builder as IQueryBuilder;
use professionalweb\IntegrationHub\IntegrationHubDB\Traits\RepositoryHasStaticEvents;
use professionalweb\IntegrationHub\IntegrationHubDB\Interfaces\Repositories\Repository;

/**
 * Basic abstract repository
 */
abstract class BaseRepository implements Repository
{
    use RepositoryHasStaticEvents;

    protected const int DEFAULT_CACHE_TTL = 1800;

    /** @var array */
    protected static $events = [];

    /**
     * @var string
     */
    private string $modelClass;

    /** @var array */
    protected static $conditions = [];

    /** @var bool */
    protected bool $dependsOnUser = false;

    /** @var bool */
    protected bool $useConditions = true;

    protected bool $withTrashed = false;

    /**
     * Create query
     *
     * @param array $filters
     * @param array $order
     * @param int|null $limit
     * @param int|null $offset
     * @return Builder
     */
    protected function getQuery(array $filters = [], array $order = [], ?int $limit = null, ?int $offset = null): Builder
    {
        $modelClass = $this->getModelClass();

        /** @var Builder $query */
        $query = $modelClass::query();

        if ($this->withTrashed && method_exists($modelClass, 'withTrashed')) {
            $query->withTrashed();
        }

        if ($this->useConditions) {
            $conditions = static::getConditions();
            foreach ($conditions as $field => $value) {
                if (is_array($value) || is_object($value)) {
                    $query->whereIn($field, $value);
                } else {
                    $query->where($field, $value);
                }
            }
        }

        foreach ($filters as $key => $val) {
            if (is_array($val)) {
                $query->whereIn($key, $val);
            } else {
                $query->where($key, $val);
            }
        }

        foreach ($order as $column => $direction) {
            $query->orderBy($column, $direction);
        }
        if ($limit !== null) {
            $query->limit($limit);
        }
        if ($offset !== null) {
            $query->offset($offset);
        }

        return $query;
    }

    /**
     * Count items by filter
     *
     * @param array $filters
     *
     * @return int
     */
    public function count(array $filters = []): int
    {
        $cacheKey = $this->prepareCacheKey($filters);
        $tags = $this->getCacheTags();
        if (Cache::tags($tags)->has($cacheKey)) {
            return Cache::tags($tags)->get($cacheKey);
        }
        $query = $this->getQuery($filters);

        Cache::tags($tags)->put($cacheKey, $result = $query->count(), self::DEFAULT_CACHE_TTL);

        return $result;
    }

    /**
     * Get data
     *
     * @param array $filters
     * @param array $sort
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return Collection
     */
    public function get(array $filters = [], array $sort = [], ?int $limit = null, ?int $offset = null): Collection
    {
        $tags = $this->getCacheTags();
        $cacheKey = $this->prepareCacheKey([$filters, $sort, [$limit, $offset]]);
        if (Cache::tags($tags)->has($cacheKey)) {
            return Cache::tags($tags)->get($cacheKey, collect());
        }

        $query = $this->getQuery($filters, $sort, $limit, $offset);

        Cache::tags($tags)->put($cacheKey, $result = $query->get(), self::DEFAULT_CACHE_TTL);

        return $result;
    }

    /**
     * Get data with pagination
     *
     * @param array $filters
     * @param array $sort
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return Paginator
     */
    public function pagination(array $filters = [], array $sort = [], ?int $limit = WithPagination::PAGINATION_DEFAULT_LIMIT, ?int $offset = 0): Paginator
    {
        $qty = $this->count($filters);
        $items = $qty > 0 ? $this->get($filters, $sort, $limit, $offset) : [];

        return new LengthAwarePaginator($items, $qty, $limit, floor($offset / $limit) + 1, [
            'path' => BasePaginator::resolveCurrentPath(),
        ]);
    }

    /**
     * Set model class
     *
     * @param string $className
     *
     * @return BaseRepository
     */
    public function setModelClass(string $className): self
    {
        $this->modelClass = $className;

        return $this;
    }

    /**
     * Create model
     *
     * @param array $attributes
     *
     * @return Model
     */
    public function create(array $attributes = []): Model
    {
        $modelClass = $this->getModelClass();
        $result = new $modelClass($attributes);

        static::fireEvent('create', $result);

        return $result;
    }

    /**
     * Save model
     *
     * @param Model $model
     *
     * @return Model
     */
    public function save(Model $model): bool
    {
        if ($result = $model->save()) {
            static::fireEvent('save', $model);
            $tags = $this->getCacheTags($model);
            Cache::tags($tags)->forever($this->prepareModelCacheKey($model), $model);
            Cache::tags($tags)->flush();
        }

        return $result;
    }

    /**
     * Remove model
     *
     * @param Model $model
     *
     * @return bool
     * @throws \Exception
     */
    public function remove(Model $model): bool
    {
        try {
            DB::beginTransaction();
            if ($result = $model->delete()) {
                static::fireEvent('delete', $model);
                $this->flush($model);
            }
            DB::commit();
        } catch (\Throwable $ex) {
            DB::rollBack();
            Log::critical($ex);

            $result = false;
        }

        return $result;
    }

    /**
     * Fill model
     *
     * @param Model $model
     * @param array $attributes
     *
     * @return Model
     */
    public function fill(Model $model, array $attributes = []): Model
    {
        return $model->fill($attributes);
    }

    /**
     * Get model by id
     *
     * @param string|int $id
     *
     * @return Model|null
     */
    public function model($id): ?Model
    {
        $model = $this->create();
        $model->id = $id;
        $cacheKey = $this->prepareModelCacheKey($model);
        $tags = $this->getCacheTags($model);
        if (!empty($model = Cache::tags($tags)->get($cacheKey))) {
            return $model;
        }

        Cache::tags($tags)->forever($cacheKey, $result = $this->getQuery()->find($id));

        return $result;
    }

    /**
     * Get model class
     *
     * @return string
     */
    public function getModelClass(): string
    {
        return $this->modelClass;
    }

    /**
     * Add scopes
     *
     * @param array $conditions
     */
    public static function addConditions(array $conditions): void
    {
        static::$conditions = array_merge(static::$conditions, $conditions);
    }

    /**
     * Get scopes
     *
     * @return array
     */
    public function getConditions(): array
    {
        $result = [];
        foreach (static::$conditions as $field => $condition) {
            $result = array_merge($result, is_callable($condition) ? $condition($this) : [$field => $condition]);
        }

        return $result;
    }

    /**
     * Get all models
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->getQuery()->get();
    }

    /**
     * Register create event
     *
     * @param callable $callback
     */
    public static function onCreate(callable $callback): void
    {
        static::registerEvent('create', $callback);
    }

    /**
     * Register save event
     *
     * @param callable $callback
     */
    public static function onSave(callable $callback): void
    {
        static::registerEvent('save', $callback);
    }

    /**
     * Register delete event
     *
     * @param callable $callback
     */
    public static function onDelete(callable $callback): void
    {
        static::registerEvent('delete', $callback);
    }

    /**
     * Prepare cache key
     *
     * @param array $params
     *
     * @return string
     */
    protected function prepareCacheKey(array $params): string
    {
        $result = get_class($this);
        $params = array_merge($params, array_map(function ($item) {
            return $item instanceof IQueryBuilder ? $item->toSql() . implode('.', $item->getBindings()) : $item;
        }, self::getConditions()));

        ksort($params);
        $result .= json_encode($params);

        return md5($result);
    }

    /**
     * @param Model $model
     *
     * @return string
     */
    protected function prepareModelCacheKey(Model $model): string
    {
        return md5(get_class($model) . $model->id);
    }

    /**
     * @param Model|null $model
     *
     * @return array
     */
    protected function getCacheTags(?Model $model = null): array
    {
        $result = [get_class($this)];
        if (!empty($company = request()->attributes->get('company'))) {
            $result[] = $company->id;
        }
        if ($this->dependsOnUser) {
            $result[] = Auth::id();
        }

        return $result;
    }

    /**
     * Get repository without conditions
     *
     * @return $this
     */
    public function withoutConditions($flag = false): Repository
    {
        $this->useConditions = $flag;

        return $this;
    }

    /**
     * Flush cache
     *
     * @param Model|null $model
     */
    public function flush(?Model $model): void
    {
        if ($model !== null) {
            Cache::forget($this->prepareModelCacheKey($model));
        }
        Cache::tags($this->getCacheTags($model))->flush();
    }

    /**
     * Select with trashed
     *
     * @param bool $flag
     * @return self
     */
    public function withTrashed(bool $flag = true): self
    {
        $this->withTrashed = $flag;

        return $this;
    }
}
