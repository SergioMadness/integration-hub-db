<?php namespace professionalweb\IntegrationHub\IntegrationHubDB\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use professionalweb\IntegrationHub\IntegrationHub\Models\Application;
use professionalweb\IntegrationHub\IntegrationHubDB\Abstractions\UUIDModel;
use professionalweb\IntegrationHub\IntegrationHubCommon\Interfaces\EventData;
use professionalweb\IntegrationHub\IntegrationHubDB\Interfaces\Model as IModel;

/**
 * Request
 * @package App\Models
 *
 * @property string      $id
 * @property string      $application_id
 * @property array       $body
 * @property array       $processing_info
 * @property string      $status
 * @property string      $response
 * @property string      $request_type
 * @property string      $created_at
 * @property string      $updated_at
 *
 * @property Application $application
 */
class Request extends UUIDModel implements IModel, EventData
{
    public const STATUS_NEW = 'new';

    public const STATUS_QUEUE = 'queue';

    public const STATUS_SUCCESS = 'success';

    public const STATUS_FAILED = 'failed';

    public const STATUS_RETRY = 'need_another_attempt';

    public const DEFAULT_TYPE = 'request';

    protected $table = 'requests';

    protected $fillable = [
        'application_id',
        'body',
        'request_type',
    ];

    protected $casts = [
        'body'            => 'array',
        'processing_info' => 'array',
        'response'        => 'array',
    ];

    protected $visible = [
        'id',
        'body',
        'status',
        'request_type',
        'processing_info',
        'response',
        'created_at',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->status)) {
                $model->status = self::STATUS_NEW;
            }
        });
    }

    /**
     * Application relation
     *
     * @return BelongsTo
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    /**
     * increment attempts
     *
     * @return Request
     */
    public function incAttempts(): self
    {
        $data = $this->processing_info;
        $data['attempts'] = isset($data['attempts']) ? ++$data['attempts'] : 1;
        $this->processing_info = $data;

        return $this;
    }

    public function toArray(): array
    {
        $result = parent::toArray();

        $result['application'] = $this->application;

        return $result;
    }

    /**
     * Get current step
     *
     * @return string
     */
    public function getCurrentFlow(): string
    {
        return $this->processing_info['current_flow'] ?: '';
    }

    /**
     * Get current flow id
     *
     * @return string
     */
    public function getCurrentStep(): string
    {
        return $this->processing_info['current_step'] ?: '';
    }

    /**
     * Set current step id in flow
     *
     * @param string $flowId
     * @param string $stepId
     *
     * @return Request
     */
    public function setCurrentStep(string $flowId, string $stepId): self
    {
        $processingInfo = $this->processing_info;

        $processingInfo['current_flow'] = $flowId;
        $processingInfo['current_step'] = $stepId;

        $this->processing_info = $processingInfo;

        return $this;
    }

    /**
     * Set process response
     *
     * @param string $processId
     * @param bool   $processSucceed
     * @param mixed  $processResponse
     *
     * @return Request
     */
    public function setProcessResponse(string $processId, $processResponse, bool $processSucceed = true): self
    {
        $processingInfo = $this->processing_info;

        if (!isset($processingInfo['process_response'])) {
            $processingInfo['process_response'] = [];
        }
        $processingInfo['process_response'][$processId] = [
            'processId' => $processId,
            'isError'   => !$processSucceed,
            'response'  => $processResponse,
        ];

        $this->processing_info = $processingInfo;

        return $this;
    }

    /**
     * Get data
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->body;
    }

    /**
     * Set data
     *
     * @param $data
     *
     * @return mixed
     */
    public function setData($data)
    {
        $this->body = $data;

        return $this;
    }

    /**
     * Get event id
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
//
//    /**
//     * To prepare body attribute
//     *
//     * @param string $key
//     * @param mixed  $value
//     *
//     * @return mixed
//     */
//    public function setAttribute($key, $value)
//    {
//        if ($key === 'body') {
//            $body = [];
//            $value = (array)$value;
//            foreach ($value as $paramKey => $paramValue) {
//                $body[mb_strtolower($paramKey)] = $paramValue;
//            }
//            $value = $body;
//        }
//
//        return parent::setAttribute($key, $value);
//    }
}