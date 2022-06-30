<?php namespace professionalweb\IntegrationHub\IntegrationHubDB\Repositories;

use professionalweb\lms\Common\Abstractions\BaseRepository;
use professionalweb\IntegrationHub\IntegrationHubDB\Models\Flow as FlowModel;
use professionalweb\IntegrationHub\IntegrationHubCommon\Interfaces\Models\Flow;
use professionalweb\IntegrationHub\IntegrationHubCommon\Interfaces\Repositories\FlowRepository as IFlowRepository;

/**
 * Repository to work with event flows
 * @package professionalweb\IntegrationHub\IntegrationHubDB\Repositories
 *
 * @method save(Flow $model): bool
 * @method create(array $attributes = []): Flow
 * @method remove(Flow $model): bool
 * @method fill(Flow $model, array $attributes = []): Flow
 */
class FlowRepository extends BaseRepository implements IFlowRepository
{
    public function __construct()
    {
        $this->setModelClass(FlowModel::class);
    }

    /**
     * Get default processing flow
     *
     * @return Flow|null
     */
    public function getDefault(): ?Flow
    {
        /** @var Flow $defaultFlow */
        $defaultFlow = FlowModel::query()->where('is_default', true)->where('is_active', true)->first();

        return $defaultFlow;
    }
}