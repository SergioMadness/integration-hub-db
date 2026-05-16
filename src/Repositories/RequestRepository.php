<?php

declare(strict_types=1);

namespace professionalweb\IntegrationHub\IntegrationHubDB\Repositories;

use Illuminate\Database\Eloquent\Model;
use professionalweb\IntegrationHub\IntegrationHubDB\Models\Request;
use professionalweb\IntegrationHub\IntegrationHubDB\Abstractions\EntityRepository;
use professionalweb\IntegrationHub\IntegrationHubCommon\Interfaces\Repositories\RequestRepository as IRequestRepository;

/**
 * Repository of requests
 *
 * @method save(Request $model): bool
 * @method model($id): ?Request
 * @method remove(Request $model): bool
 * @method fill(Request $model, array $attributes = []): Request
 */
class RequestRepository extends EntityRepository implements IRequestRepository
{
    public bool $noNeedWebsite = true;

    public function __construct()
    {
        $this->setModelClass(Request::class);
    }

    /**
     * Create model; Set default type
     *
     * @return Request
     */
    public function create(array $attributes = []): Model
    {
        if (!isset($attributes['request_type'])) {
            $attributes['request_type'] = Request::DEFAULT_TYPE;
        }

        return parent::create($attributes);
    }
}