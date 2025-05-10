<?php

namespace Bijanbiria\LaravelDeployWizard;

use Illuminate\Support\Facades\DB;

class DeployWizard
{
    /**
     * Check if the application is already installed
     *
     * @return bool
     */
    public static function isInstalled(): bool
    {
        return file_exists(base_path('.env')) && DB::connection()->getDatabaseName();
    }

    /**
     * Redirect to installer if not installed
     *
     * @return void
     */
    public static function checkInstallation(): void
    {
        if (!self::isInstalled()) {
            header('Location: ' . url('/deploy-wizard'));
            exit;
        }
    }
}