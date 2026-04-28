<?php

declare(strict_types=1);

namespace professionalweb\IntegrationHub\IntegrationHubDB\Interfaces\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\Paginator;

/**
 * Basic repository
 */
interface Repository
{
    /**
     * Create model
     */
    public function create(array $attributes = []): Model;

    /**
     * Save model
     */
    public function save(Model $model): bool;

    /**
     * Remove model
     */
    public function remove(Model $model): bool;

    /**
     * Fill model
     */
    public function fill(Model $model, array $attributes = []): Model;

    /**
     * Get model by id
     */
    public function model($id): ?Model;

    /**
     * Get data
     */
    public function get(array $filters = [], array $sort = [], ?int $limit = null, ?int $offset = null): Collection;

    /**
     * Get data with pagination
     */
    public function pagination(array $filters = [], array $sort = [], ?int $limit = null, ?int $offset = null): Paginator;

    /**
     * Count items by filter
     */
    public function count(array $filters = []): int;

    /**
     * Add scopes
     */
    public static function addConditions(array $conditions): void;

    /**
     * Get all models
     */
    public function all(): Collection;

    /**
     * Get repository without conditions
     */
    public function withoutConditions(bool $flag = true): self;

    /**
     * Select with trashed
     */
    public function withTrashed(bool $flag = true): self;
}