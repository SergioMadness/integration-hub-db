<?php namespace professionalweb\IntegrationHub\IntegrationHubDB\Interfaces\Repositories;

use professionalweb\IntegrationHub\IntegrationHubDB\Models\Flow;
use professionalweb\IntegrationHub\IntegrationHubDB\Interfaces\Model;
use professionalweb\IntegrationHub\IntegrationHubDB\Interfaces\Repository;

/**
 * Flow repository interface
 * @package professionalweb\IntegrationHub\IntegrationHubDB\Interfaces\Repositories
 *
 * @method create(array $attributes = []): Flow
 * @method fill(Model $model, array $attributes = []): Flow
 * @method model($id): ?Flow
 */
interface FlowRepository extends Repository
{
    /**
     * Get default processing flow
     *
     * @return Flow|null
     */
    public function getDefault(): ?Flow;
}