<?php

declare(strict_types=1);

namespace professionalweb\IntegrationHub\IntegrationHubDB\Abstractions;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

/**
 * Basic class for models with uuid IDs
 */
abstract class UUIDModel extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->keyType = 'string';
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            /** @var UUIDModel $model */
            if (empty($model->{$model->getKeyName()})) {
                $model->generateId();
            }
        });
    }

    /**
     * generate UUID
     *
     * @return string
     * @throws \Exception
     */
    public function generateId(): string
    {
        return $this->{$this->getKeyName()} = Str::uuid()->toString();
    }
}