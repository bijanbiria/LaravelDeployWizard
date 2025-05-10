<?php

namespace Bijanbiria\LaravelDeployWizard\Providers;

use Bijanbiria\LaravelDeployWizard\Http\Middleware\CheckInstallation;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Request;

class DeployWizardServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @param Router $router
     * @return void
     */
    public function boot(Router $router): void
    {
        // ✅ Load the routes for the deployment wizard
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');

        // ✅ Load the views for the deployment wizard with the namespace 'deployWizard'
        $this->loadViewsFrom(__DIR__ . '/../Views', 'deployWizard');

        // ✅ Publish the configuration file to Laravel's config path
        $this->publishes([
            __DIR__ . '/../Config/deploywizard.php' => config_path('deploywizard.php'),
        ], 'deploy-wizard-config');

        // ✅ Publish the views to Laravel's resources path for customization
        $this->publishes([
            __DIR__ . '/../Views' => resource_path('views/vendor/deploy-wizard'),
        ], 'deploy-wizard-views');

        // ✅ Check if the .env file exists
        if (!File::exists(base_path('.env'))) {
            // If not, copy the .env.example as .env
            if (File::exists(base_path('.env.example'))) {
                File::copy(base_path('.env.example'), base_path('.env'));

                // 🔄 Redirect to the same page to load the new .env
                header('Location: ' . Request::url());
                exit;
            }
        }

        // ✅ If the .env exists but the APP_KEY is missing, generate a new one
        if (File::exists(base_path('.env')) && empty(env('APP_KEY'))) {
            // Generate application key
            Artisan::call('key:generate');

            // Run database migrations
            Artisan::call('migrate --force');

            // Run database seeders
            Artisan::call('db:seed --force');

            // 🔄 Redirect to the deployment wizard for further setup
            header('Location: /deploy-wizard');
            exit;
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        // ✅ Merge package configuration with the application's published config
        // If the developer publishes the config, their changes will override the defaults
        $this->mergeConfigFrom(__DIR__ . '/../Config/deploywizard.php', 'deploywizard');
    }
}
