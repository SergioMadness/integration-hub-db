<?php namespace professionalweb\IntegrationHub\IntegrationHubDB\Repositories;

use Ramsey\Uuid\Uuid;
use Illuminate\Support\Collection;
use professionalweb\IntegrationHub\IntegrationHubDB\Models\Flow;
use professionalweb\IntegrationHub\IntegrationHubDB\Interfaces\Model;
use professionalweb\IntegrationHub\IntegrationHubDB\Interfaces\Repositories\FlowRepository as IFlowRepository;

/**
 * Repository to work with event flows
 * @package professionalweb\IntegrationHub\IntegrationHubDB\Repositories
 *
 * @method save(Flow $model): bool
 * @method create(array $attributes = []): Flow
 * @method model($id): ?Flow
 * @method remove(Flow $model): bool
 * @method fill(Flow $model, array $attributes = []): Flow
 */
class FlowRepository extends BaseRepository implements IFlowRepository
{
    /**
     * @var Collection
     */
    private $staticCollection;

    public function __construct(Collection $collection = null)
    {
        $this
            ->setStaticCollection($collection ?? collect([]))
            ->setModelClass(Flow::class);
    }

    /**
     * Get default processing flow
     *
     * @return Flow|null
     */
    public function getDefault(): ?Flow
    {
        /** @var Flow $defaultFlow */
        $defaultFlow = Flow::query()->where('is_default', true)->where('is_active', true)->first();

        if ($defaultFlow === null) {
            $defaultFlow = $this->getStaticCollection()->firstWhere('is_default', true);
        }

        return $defaultFlow;
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