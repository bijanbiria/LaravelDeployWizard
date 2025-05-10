<?php

namespace Bijanbiria\LaravelDeployWizard\Providers;

use Illuminate\Support\ServiceProvider;

class DeployWizardServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../Views', 'deployWizard');

        $this->publishes([
            __DIR__ . '/../Config/deployWizard.php' => config_path('deployWizard.php'),
        ], 'config');

        if (!file_exists(config_path('deployWizard.php'))) {
            $this->mergeConfigFrom(__DIR__ . '/../Config/deployWizard.php', 'deployWizard');
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../Config/deployWizard.php', 'deployWizard');
    }
}