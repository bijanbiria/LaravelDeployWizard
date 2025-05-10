<?php

namespace Bijanbiria\LaravelDeployWizard\Providers;

use Illuminate\Support\ServiceProvider;

class DeployWizardServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load Routes
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');

        // Load Views
        $this->loadViewsFrom(__DIR__ . '/../Views', 'deployWizard');

        // Publish Config
        $this->publishes([
            __DIR__ . '/../Config/deployWizard.php' => config_path('deployWizard.php'),
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../Config/deployWizard.php', 'deployWizard');
    }
}