<?php

declare(strict_types=1);

namespace professionalweb\IntegrationHub\IntegrationHubDB\Repositories;

use professionalweb\IntegrationHub\IntegrationHubDB\Models\ProcessOptions;
use professionalweb\IntegrationHub\IntegrationHubDB\Abstractions\EntityRepository;
use professionalweb\IntegrationHub\IntegrationHubCommon\Interfaces\Repositories\ProcessOptionsRepository as IProcessOptionsRepository;

/**
 * Process options repository
 *
 * @method save(ProcessOptions $model): bool
 * @method create(array $attributes = []): ProcessOptions
 * @method remove(ProcessOptions $model): bool
 * @method fill(ProcessOptions $model, array $attributes = []): ProcessOptions
 */
class ProcessOptionsRepository extends EntityRepository implements IProcessOptionsRepository
{
    public bool $noNeedWebsite = true;

    public function __construct()
    {
        $this->setModelClass(ProcessOptions::class);
    }
}