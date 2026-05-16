<?php

declare(strict_types=1);

namespace professionalweb\IntegrationHub\IntegrationHubDB\Abstractions;

use professionalweb\lms\Common\Models\Model;

/**
 * Base class for repositories of system entities
 * @package professionalweb\lms\Common\Abstractions
 */
abstract class EntityRepository extends BaseRepository
{
    /** @var array */
    protected static $events = [];

    /** @var array */
    private static $observers = [];

    /** @var array */
    protected static $conditions = [];

    public function setModelClass(string $className): BaseRepository
    {
        /** @var Model $className */
        foreach (self::$observers as $observer) {
            $className::observe($observer);
        }

        return parent::setModelClass($className);
    }

    /**
     * Add callbacks for entity models
     *
     * @param $observer
     */
    public static function addObserver($observer): void
    {
        self::$observers[] = $observer;
    }
}
