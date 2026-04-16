<?php

declare(strict_types=1);

namespace professionalweb\IntegrationHub\IntegrationHubDB\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use professionalweb\lms\Common\Abstractions\UUIDModel;
use professionalweb\lms\Common\Interfaces\Models\Model;
use professionalweb\IntegrationHub\IntegrationHubCommon\Interfaces\Models\ProcessOptions as IProcessOptions;

/**
 * Process options
 * @package App\Models
 *
 * @property string $id
 * @property string $subsystem_id
 * @property string $name
 * @property array $mapping
 * @property array $options
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class ProcessOptions extends UUIDModel implements IProcessOptions, Model
{
    use SoftDeletes;

    public $keyType = 'string';
    protected $table = 'process_options';
    protected $casts = [
        'mapping' => 'array',
        'options' => 'array',
    ];

    protected $fillable = [
        'name',
        'mapping',
        'options',
        'subsystem_id',
    ];

    /**
     * Get data mapping
     *
     * @return array
     */
    public function getMapping(): array
    {
        return $this->mapping ?? [];
    }

    /**
     * Processor is remote
     *
     * @return bool
     */
    public function isRemote(): bool
    {
        return $this->getOptions()['is_remote'] ?? false;
    }

    /**
     * Get process options
     *
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options ?? [];
    }

    /**
     * Get queue name to send event to processor through queue
     *
     * @return string
     */
    public function getQueue(): string
    {
        return $this->getOptions()['queue'] ?? '';
    }

    /**
     * Get host to send event to processor through REST API
     *
     * @return string
     */
    public function getHost(): string
    {
        return $this->getOptions()['host'] ?? '';
    }

    /**
     * Get class name to identify processor
     *
     * @return string
     */
    public function getSubsystemId(): string
    {
        return $this->subsystem_id;
    }

    /**
     * Get process id
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Need to stop on fail
     *
     * @return bool
     */
    public function stopOnFail(): bool
    {
        return $this->getOptions()['stop_on_fail'] ?? false;
    }
}