<?php namespace professionalweb\IntegrationHub\IntegrationHubDB\Providers;

use Illuminate\Support\ServiceProvider;
use professionalweb\IntegrationHub\IntegrationHubDB\Repositories\RequestRepository;
use professionalweb\IntegrationHub\IntegrationHubDB\Interfaces\Repositories\RequestRepository as IRequestRepository;

class IntegrationHubDBProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(IRequestRepository::class, RequestRepository::class);
    }
}