<?php namespace professionalweb\IntegrationHub\IntegrationHubDB\Traits;

use professionalweb\IntegrationHub\IntegrationHubDB\Interfaces\Repositories\FlowRepository;

/**
 * Trait for classes that use flow repository
 * @package professionalweb\IntegrationHub\IntegrationHubDB\Traits
 */
trait UseFlowRepository
{
    /**
     * @var FlowRepository
     */
    private $flowRepository;

    /**
     * @return FlowRepository
     */
    public function getFlowRepository(): FlowRepository
    {
        return $this->flowRepository;
    }

    /**
     * @param FlowRepository $flowRepository
     *
     * @return $this
     */
    public function setFlowRepository(FlowRepository $flowRepository): self
    {
        $this->flowRepository = $flowRepository;

        return $this;
    }
}