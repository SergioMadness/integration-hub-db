<?php

declare(strict_types=1);

namespace professionalweb\IntegrationHub\IntegrationHubDB\Models;

use Exception;
use Illuminate\Database\Eloquent\SoftDeletes;
use professionalweb\lms\Common\Abstractions\UUIDModel;
use professionalweb\lms\Common\Interfaces\Models\Model;
use professionalweb\IntegrationHub\IntegrationHubCommon\Interfaces\Models\FlowStep;
use professionalweb\IntegrationHub\IntegrationHubDB\Models\FlowStep as FlowStepModel;
use professionalweb\IntegrationHub\IntegrationHubCommon\Interfaces\Models\Flow as IFlow;
use function count;

/**
 * Process flow model
 * @package App\Models
 *
 * @property string $id
 * @property string $name
 * @property array $data
 * @property boolean $is_default
 * @property boolean $is_active
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class Flow extends UUIDModel implements IFlow, Model
{
    use SoftDeletes;

    protected $table = 'flow';

    protected $casts = [
        'data' => 'array',
    ];

    protected $fillable = [
        'name',
        'is_default',
        'is_active',
        'data',
    ];

    /**
     * Get next node
     *
     * @return FlowStep
     * @throws Exception
     */
    public function getNext(string $id): ?FlowStep
    {
        if (empty($id) || !isset($this->data[$id])) {
            return $this->head();
        }

        $nextId = $this->getNode($id)->getNextId();

        return empty($nextId) ? null : $this->getNode($nextId);
    }

    /**
     * Get first node
     *
     * @return null|FlowStep
     * @throws Exception
     */
    public function head(): ?FlowStep
    {
        return $this->getNode(array_keys($this->data)[0] ?? '');
    }

    /**
     * Get node by id
     *
     * @return FlowStep
     * @throws Exception
     */
    public function getNode(string $id): ?FlowStep
    {
        static $cache = [];
        if (isset($cache[$id])) {
            return $cache[$id];
        }

        $data = $this->data;
        if (!isset($data[$id])) {
            return null;
        }
        if (is_array($data[$id])) {
            $cache[$id] = $this->makeFlowStep($data[$id]);
        } else {
            $cache[$id] = $data[$id];
        }

        return $cache[$id];
    }

    /**
     * Translate array to flow step model
     */
    protected function makeFlowStep(array $itemData): FlowStep
    {
        return (new FlowStepModel())
            ->setId($itemData['id'])
            ->setNextId((array)$itemData['next'])
            ->setPrevId((array)$itemData['prev'])
            ->setConditions($itemData['condition'] ?? [])
            ->setSubsystemId($itemData['subsystem'] ?? $itemData['id']);
    }

    /**
     * Get previous step
     *
     * @return FlowStep
     * @throws Exception
     */
    public function getPrev(string $id): ?FlowStep
    {
        $prevId = $this->getNode($id)->getPrevId();

        return empty($prevId) ? null : $this->getNode($prevId);
    }

    /**
     * Check next step has condition
     *
     * @throws Exception
     */
    public function isConditional(string $id): bool
    {
        return !empty($this->getNode($id)->getConditions());
    }

    /**
     * Get condition for flow step
     *
     * @throws Exception
     */
    public function getCondition(string $id): array
    {
        return $this->getNode($id)->getConditions();
    }

    /**
     * Add node
     */
    public function addNode(FlowStep $step): IFlow
    {
        $this->data[$step->getId()] = $step;

        return $this;
    }

    /**
     * Remove node
     */
    public function removeNode(string $id): IFlow
    {
        try {
            $node = $this->getNode($id);

            if (!empty($prev = $node->getPrevId())) {
                $this->getNode($prev)->setNextId([$node->getNextId()]);
            }
            if (!empty($next = $node->getNextId())) {
                $this->getNode($prev)->setPrevId([$node->getPrevId()]);
            }
        } catch (Exception $e) {
        }

        unset($this->data[$id]);

        return $this;
    }

    /**
     * Get last node
     *
     * @return null|FlowStep
     * @throws Exception
     */
    public function tail(): ?FlowStep
    {
        return $this->getNode(array_keys($this->data)[count($this->data) - 1] ?? '');
    }
}