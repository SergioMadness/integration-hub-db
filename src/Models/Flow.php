<?php namespace professionalweb\IntegrationHub\IntegrationHubDB\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use professionalweb\IntegrationHub\IntegrationHubDB\Interfaces\Model;
use professionalweb\IntegrationHub\IntegrationHubDB\Abstractions\UUIDModel;
use professionalweb\IntegrationHub\IntegrationHubCommon\Interfaces\Models\Flow as IFlow;

/**
 * Process flow model
 * @package App\Models
 *
 * @property string  $id
 * @property string  $name
 * @property array   $data
 * @property boolean $is_default
 * @property boolean $is_active
 * @property string  $created_at
 * @property string  $updated_at
 * @property string  $deleted_at
 */
class Flow extends UUIDModel implements IFlow, Model
{
    use SoftDeletes;

    protected $table = 'flow';

    public $keyType = 'string';

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
     * @param string $id
     *
     * @return string
     */
    public function getNext(string $id): ?string
    {
        if (empty($id) || !isset($this->data[$id])) {
            return $this->head();
        }

        return isset($this->data[$id]['next']) ? array_first($this->data[$id]['next']) : null;
    }

    /**
     * Get previous step
     *
     * @param string $id
     *
     * @return string
     */
    public function getPrev(string $id): ?string
    {
        return isset($this->data[$id]['prev']) ? array_first($this->data[$id]['prev']) : null;
    }

    /**
     * Check next step has condition
     *
     * @param string $id
     *
     * @return bool
     */
    public function isConditional(string $id): bool
    {
        return isset($this->data[$id]['condition']) && !empty($this->data[$id]['condition']);
    }

    /**
     * Get condition for flow step
     *
     * @param string $id
     *
     * @return array
     */
    public function getCondition(string $id): array
    {
        return $this->data[$id]['condition'] ?? [];
    }

    /**
     * Add node
     *
     * @param string      $id
     * @param null|string $next
     * @param null|string $prev
     *
     * @return IFlow
     */
    public function addNode(string $id, ?string $next, ?string $prev): IFlow
    {
        $this->data[$id] = [
            'next' => $next,
            'prev' => $prev,
        ];

        return $this;
    }

    /**
     * Remove node
     *
     * @param string $id
     *
     * @return IFlow
     */
    public function removeNode(string $id): IFlow
    {
        $data = $this->data;

        if ($data[$id]['prev'] !== null) {
            $data[$data[$id]['prev']]['next'] = $data[$id]['next'];
        }
        if ($data[$id]['next'] !== null) {
            $data[$data[$id]['next']]['prev'] = $data[$id]['prev'];
        }
        unset($data[$id]);

        $this->data = $data;

        return $this;
    }

    /**
     * Get first node
     *
     * @return null|string
     */
    public function head(): ?string
    {
        return array_keys($this->data)[0] ?? null;
    }

    /**
     * Get last node
     *
     * @return null|string
     */
    public function tail(): ?string
    {
        return array_keys($this->data)[\count($this->data) - 1] ?? null;
    }
}