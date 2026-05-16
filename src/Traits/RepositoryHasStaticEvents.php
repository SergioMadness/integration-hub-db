<?php

declare(strict_types=1);

namespace professionalweb\IntegrationHub\IntegrationHubDB\Traits;

use Illuminate\Database\Eloquent\Model;

/**
 * Trait for classes has static events
 */
trait RepositoryHasStaticEvents
{
    protected static $events = [];

    /**
     * Register event
     */
    protected static function registerEvent(string $event, callable $callable): void
    {
        if (!isset(static::$events[$event])) {
            static::$events[$event] = [];
        }
        static::$events[$event][] = $callable;
    }

    /**
     * Fire event
     */
    protected static function fireEvent(string $event, Model $model): mixed
    {
        $result = null;
        $events = static::$events[$event] ?? [];
        foreach ($events as $callable) {
            $result = $callable($model);
        }

        return $result;
    }
}