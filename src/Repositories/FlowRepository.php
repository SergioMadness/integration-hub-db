<?php

declare(strict_types=1);

namespace professionalweb\IntegrationHub\IntegrationHubDB\Repositories;

use professionalweb\IntegrationHub\IntegrationHubDB\Models\Flow as FlowModel;
use professionalweb\IntegrationHub\IntegrationHubCommon\Interfaces\Models\Flow;
use professionalweb\IntegrationHub\IntegrationHubDB\Abstractions\EntityRepository;
use professionalweb\IntegrationHub\IntegrationHubCommon\Interfaces\Repositories\FlowRepository as IFlowRepository;

/**
 * Repository to work with event flows
 *
 * @method save(Flow $model): bool
 * @method create(array $attributes = []): Flow
 * @method remove(Flow $model): bool
 * @method fill(Flow $model, array $attributes = []): Flow
 */
class FlowRepository extends EntityRepository implements IFlowRepository
{
    public bool $noNeedWebsite = true;

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