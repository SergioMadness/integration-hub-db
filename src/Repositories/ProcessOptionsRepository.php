<?php namespace professionalweb\IntegrationHub\IntegrationHubDB\Repositories;

use Ramsey\Uuid\Uuid;
use Illuminate\Support\Collection;
use professionalweb\IntegrationHub\IntegrationHubDB\Interfaces\Model;
use professionalweb\IntegrationHub\IntegrationHubDB\Models\ProcessOptions;
use professionalweb\IntegrationHub\IntegrationHubDB\Models\ProcessOptions\TransitProcessOptions;
use professionalweb\IntegrationHub\IntegrationHubDB\Interfaces\Repositories\ProcessOptionsRepository as IProcessOptionsRepository;

/**
 * Process options repository
 * @package professionalweb\IntegrationHub\IntegrationHubDB\Repositories
 *
 * @method save(ProcessOptions $model): bool
 * @method create(array $attributes = []): ProcessOptions
 * @method remove(ProcessOptions $model): bool
 * @method fill(ProcessOptions $model, array $attributes = []): ProcessOptions
 */
class ProcessOptionsRepository extends BaseRepository implements IProcessOptionsRepository
{
    /**
     * @var Collection
     */
    private $staticCollection;

    public function __construct(Collection $collection = null)
    {
        $this
            ->setStaticCollection($collection ?? collect([]))
            ->setModelClass(ProcessOptions::class);
    }

    /**
     * @param int|string $id
     *
     * @return null|Model
     */
    public function model($id): ?Model
    {
        $result = Uuid::isValid($id) ? parent::model($id) : null;

        if ($result === null) {
            $result = $this->getStaticCollection()->firstWhere('id', $id);
        }
        if ($result === null) {
            $result = new TransitProcessOptions($id);
        }

        return $result;
    }

    /**
     * @return Collection
     */
    public function getStaticCollection(): Collection
    {
        return $this->staticCollection;
    }

    /**
     * @param Collection $staticCollection
     *
     * @return $this
     */
    public function setStaticCollection(Collection $staticCollection): self
    {
        $this->staticCollection = $staticCollection;

        return $this;
    }
}