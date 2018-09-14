<?php namespace professionalweb\IntegrationHub\IntegrationHubDB\Traits;

use professionalweb\IntegrationHub\IntegrationHubDB\Interfaces\Repositories\ProcessOptionsRepository;

/**
 * Trait for classes that use process options repository
 * @package professionalweb\IntegrationHub\IntegrationHubDB\Traits
 */
trait UseProcessOptionsRepository
{
    /**
     * @var ProcessOptionsRepository
     */
    private $processOptionsRepository;

    /**
     * @return ProcessOptionsRepository
     */
    public function getProcessOptionsRepository(): ProcessOptionsRepository
    {
        return $this->processOptionsRepository;
    }

    /**
     * @param ProcessOptionsRepository $processOptionsRepository
     *
     * @return $this
     */
    public function setProcessOptionsRepository(ProcessOptionsRepository $processOptionsRepository): self
    {
        $this->processOptionsRepository = $processOptionsRepository;

        return $this;
    }
}