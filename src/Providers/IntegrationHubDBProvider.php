<?php namespace professionalweb\IntegrationHub\IntegrationHubDB\Providers;

use Illuminate\Support\ServiceProvider;
use professionalweb\IntegrationHub\IntegrationHubDB\Repositories\FlowRepository;
use professionalweb\IntegrationHub\IntegrationHubDB\Repositories\RequestRepository;
use professionalweb\IntegrationHub\IntegrationHubDB\Repositories\ProcessOptionsRepository;
use professionalweb\IntegrationHub\IntegrationHubCommon\Interfaces\Repositories\FlowRepository as IFlowRepository;
use professionalweb\IntegrationHub\IntegrationHubCommon\Interfaces\Repositories\RequestRepository as IRequestRepository;
use professionalweb\IntegrationHub\IntegrationHubCommon\Interfaces\Repositories\ProcessOptionsRepository as IProcessOptionsRepository;

class IntegrationHubDBProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    public function register(): void
    {
        $this->app->singleton(IRequestRepository::class, RequestRepository::class);
        $this->app->singleton(IFlowRepository::class, function () {
            return new FlowRepository(collect(config('flow-collection')));
        });
        $this->app->singleton(IProcessOptionsRepository::class, function () {
            return new ProcessOptionsRepository(collect(config('process-options-collection')));
        });
    }
}