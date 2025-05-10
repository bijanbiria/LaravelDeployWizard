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
    public function boot(Router $router): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../Views', 'deployWizard');
        $this->publishes([
            __DIR__ . '/../Config/deployWizard.php' => config_path('deployWizard.php'),
            __DIR__ . '/../Public' => public_path('vendor/deploy-wizard')
        ], 'deploy-wizard');

        $router->aliasMiddleware('check.installation', CheckInstallation::class);

        if (!File::exists(base_path('.env'))) {
            if (File::exists(base_path('.env.example'))) {
                File::copy(base_path('.env.example'), base_path('.env'));

                header('Location: ' . Request::url());
                exit;
            }
        }

        if (File::exists(base_path('.env')) && empty(env('APP_KEY'))) {
            // 🛠 Generating APP_KEY...
            Artisan::call('key:generate');

            // ✅ Running Migrations...
            Artisan::call('migrate --force');

            // ✅ Running Seeders...
            Artisan::call('db:seed --force');

            // ✅ Application is ready!

            header('Location: /deploy-wizard');
            exit;
        }
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../Config/deployWizard.php', 'deployWizard');
    }
}