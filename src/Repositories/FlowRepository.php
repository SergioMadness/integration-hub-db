<?php namespace professionalweb\IntegrationHub\IntegrationHubDB\Repositories;

use professionalweb\IntegrationHub\IntegrationHubDB\Models\Flow;
use professionalweb\IntegrationHub\IntegrationHubDB\Interfaces\Repositories\FlowRepository as IFlowRepository;

/**
 * Repository to work with event flows
 * @package professionalweb\IntegrationHub\IntegrationHubDB\Repositories
 */
class FlowRepository extends BaseRepository implements IFlowRepository
{

    /**
     * Get default processing flow
     *
     * @return Flow|null
     */
    public function getDefault(): ?Flow
    {
        return Flow::query()->where('is_default', true)->where('is_active', true)->first();
    }
}