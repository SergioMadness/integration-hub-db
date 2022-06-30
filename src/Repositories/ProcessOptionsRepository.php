<?php namespace professionalweb\IntegrationHub\IntegrationHubDB\Repositories;

use professionalweb\lms\Common\Abstractions\BaseRepository;
use professionalweb\IntegrationHub\IntegrationHubDB\Models\ProcessOptions;
use professionalweb\IntegrationHub\IntegrationHubCommon\Interfaces\Repositories\ProcessOptionsRepository as IProcessOptionsRepository;

/**
 * Process options repository
 * @package professionalweb\IntegrationHub\IntegrationHubDB\Repositories
 *
 * @method save(ProcessOptions $model): bool
 * @method create(array $attributes = []): ProcessOptions
 * @method remove(ProcessOptions $model): bool
 * @method fill(ProcessOptions $model, array $attributes = []): ProcessOptions
 */
class ProcessOptionsRepository extends BaseRepository implements IProcessOptionsRepository
{
    public function __construct()
    {
        $this->setModelClass(ProcessOptions::class);
    }
}