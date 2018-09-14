<?php namespace professionalweb\IntegrationHub\IntegrationHubDB\Providers;

use Illuminate\Support\ServiceProvider;
use professionalweb\IntegrationHub\IntegrationHubDB\Repositories\FlowRepository;
use professionalweb\IntegrationHub\IntegrationHubDB\Repositories\RequestRepository;
use professionalweb\IntegrationHub\IntegrationHubDB\Repositories\ProcessOptionsRepository;
use professionalweb\IntegrationHub\IntegrationHubDB\Interfaces\Repositories\FlowRepository as IFlowRepository;
use professionalweb\IntegrationHub\IntegrationHubDB\Interfaces\Repositories\RequestRepository as IRequestRepository;
use professionalweb\IntegrationHub\IntegrationHubDB\Interfaces\Repositories\ProcessOptionsRepository as IProcessOptionsRepository;

class IntegrationHubDBProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(IRequestRepository::class, RequestRepository::class);
        $this->app->singleton(IFlowRepository::class, FlowRepository::class);
        $this->app->singleton(IProcessOptionsRepository::class, ProcessOptionsRepository::class);
    }
}