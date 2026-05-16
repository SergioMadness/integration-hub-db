<?php

declare(strict_types=1);

namespace professionalweb\IntegrationHub\IntegrationHubDB\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use professionalweb\IntegrationHub\IntegrationHubDB\Abstractions\UUIDModel;
use professionalweb\IntegrationHub\IntegrationHubCommon\Interfaces\Models\Model;
use professionalweb\IntegrationHub\IntegrationHubCommon\Interfaces\Models\ProcessOptions as IProcessOptions;

/**
 * Process options
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
     */
    public function getMapping(): array
    {
        return $this->mapping ?? [];
    }

    /**
     * Processor is remote
     */
    public function isRemote(): bool
    {
        return $this->getOptions()['is_remote'] ?? false;
    }

    /**
     * Get process options
     */
    public function getOptions(): array
    {
        return $this->options ?? [];
    }

    /**
     * Get queue name to send event to processor through queue
     */
    public function getQueue(): string
    {
        return $this->getOptions()['queue'] ?? '';
    }

    /**
     * Get host to send event to processor through REST API
     */
    public function getHost(): string
    {
        return $this->getOptions()['host'] ?? '';
    }

    /**
     * Get class name to identify processor
     */
    public function getSubsystemId(): string
    {
        return $this->subsystem_id;
    }

    /**
     * Get process id
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Need to stop on fail
     */
    public function stopOnFail(): bool
    {
        return $this->getOptions()['stop_on_fail'] ?? false;
    }
}