<?php namespace professionalweb\IntegrationHub\IntegrationHubDB\Repositories;

use professionalweb\IntegrationHub\IntegrationHubDB\Models\ProcessOptions;
use professionalweb\IntegrationHub\IntegrationHubDB\Interfaces\Repositories\ProcessOptionsRepository as IProcessOptionsRepository;

/**
 * Process options repository
 * @package professionalweb\IntegrationHub\IntegrationHubDB\Repositories
 */
class ProcessOptionsRepository extends BaseRepository implements IProcessOptionsRepository
{
    public function __construct()
    {
        $this->setModelClass(ProcessOptions::class);
    }
}