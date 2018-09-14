<?php namespace professionalweb\IntegrationHub\IntegrationHubDB\Repositories;

use professionalweb\IntegrationHub\IntegrationHubDB\Models\Request;
use professionalweb\IntegrationHub\IntegrationHubDB\Interfaces\Model;
use professionalweb\IntegrationHub\IntegrationHubDB\Interfaces\Repositories\RequestRepository as IRequestRepository;

/**
 * Repository of requests
 * @package professionalweb\IntegrationHub\Supervisor\Repositories
 */
class RequestRepository extends BaseRepository implements IRequestRepository
{
    public function __construct()
    {
        $this->setModelClass(Request::class);
    }

    /**
     * Create model; Set default type
     *
     * @param array $attributes
     *
     * @return Model
     */
    public function create(array $attributes = []): Model
    {
        if (!isset($attributes['request_type'])) {
            $attributes['request_type'] = Request::DEFAULT_TYPE;
        }

        return parent::create($attributes);
    }
}