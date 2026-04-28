<?php

declare(strict_types=1);

namespace professionalweb\IntegrationHub\IntegrationHubDB\Models;

use professionalweb\IntegrationHub\IntegrationHubCommon\Interfaces\Models\FlowStep as IFlowStep;

/**
 * Flow step
 */
class FlowStep implements IFlowStep
{
    private string $id = '';
    private array $nextId = [];
    private array $prevId = [];
    private string $subsystemId = '';
    private array $conditions = [];

    /**
     * Step id
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Set step id
     */
    public function setId(string $id): FlowStep
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Next step id
     */
    public function getNextId(): string
    {
        return $this->nextId[0] ?? '';
    }

    /**
     * Set next step id
     *
     * @return FlowStep
     */
    public function setNextId(array $nextId): IFlowStep
    {
        $this->nextId = $nextId;

        return $this;
    }

    /**
     * Get previous step id
     */
    public function getPrevId(): string
    {
        return $this->prevId[0] ?? '';
    }

    /**
     * Set prev step id
     *
     * @return FlowStep
     */
    public function setPrevId(array $prevId): IFlowStep
    {
        $this->prevId = $prevId;

        return $this;
    }

    /**
     * Get subsystem id
     */
    public function getSubsystemId(): string
    {
        return $this->subsystemId;
    }

    /**
     * Set subsystem id
     */
    public function setSubsystemId(string $subsystemId): FlowStep
    {
        $this->subsystemId = $subsystemId;

        return $this;
    }

    /**
     * Get conditions
     */
    public function getConditions(): array
    {
        return $this->conditions;
    }

    /**
     * Set conditions
     */
    public function setConditions(array $conditions): FlowStep
    {
        $this->conditions = $conditions;

        return $this;
    }
}
