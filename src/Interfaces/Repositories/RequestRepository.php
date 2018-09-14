<?php namespace professionalweb\IntegrationHub\IntegrationHubDB\Interfaces\Repositories;

use professionalweb\IntegrationHub\IntegrationHubDB\Interfaces\Model;
use professionalweb\IntegrationHub\IntegrationHubDB\Interfaces\Repository;

/**
 * Interface for repository of requests
 * @package professionalweb\IntegrationHub\IntegrationHubDB\Interfaces\Repositories
 *
 * @method create(array $attributes = []): Request
 * @method fill(Model $model, array $attributes = []): Request
 * @method model($id): ?Request
 */
interface RequestRepository extends Repository
{

}