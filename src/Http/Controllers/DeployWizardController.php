<?php

namespace Bijanbiria\LaravelDeployWizard\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DeployWizardController extends Controller
{
    public function show(Request $request)
    {
        return view('deployWizard::deploy-wizard', [
            'step'              => $request->input('step') ?? 1,
            'appName'           => config('app.name'),
            'appUrl'            => config('app.url'),
            'appLocale'         => config('app.locale'),
            'appFallbackLocale' => config('app.fallback_locale'),
            'appFakerLocale'    => config('app.faker_locale'),
            'dbConnection'      => env('DB_CONNECTION'),
            'dbHost'            => env('DB_HOST'),
            'dbName'            => env('DB_DATABASE'),
            'dbUser'            => env('DB_USERNAME'),
            'dbPassword'        => env('DB_PASSWORD'),
            'dbPort'            => env('DB_PORT'),
            'dbPrefix'          => env('DB_PREFIX'),
        ]);
    }

    public function storeStep1(Request $request)
    {
        $request->validate([
            'app_name' => 'required',
            'app_url'  => 'required',
        ]);

        $envPath = base_path('.env');

        // Step 1: Set Base Variables
        $this->setEnvValue('APP_NAME', $request->app_name);
        $this->setEnvValue('APP_URL', $request->app_url);
        $this->setEnvValue('APP_ENV', 'production');
        $this->setEnvValue('APP_DEBUG', 'true');

        return redirect()->route('deploy-wizard.show', ['step' => '2']);
    }

    public function storeStep2(Request $request)
    {
        $request->validate([
            'app_locale'          => 'required',
            'app_fallback_locale' => 'required',
            'app_faker_locale'    => 'required',
        ]);

        $envPath = base_path('.env');

        // Step 2: Set Locale Variables
        $this->setEnvValue('APP_LOCALE', $request->app_locale);
        $this->setEnvValue('APP_FALLBACK_LOCALE', $request->app_fallback_locale);
        $this->setEnvValue('APP_FAKER_LOCALE', $request->app_faker_locale);

        return redirect()->route('deploy-wizard.show', ['step' => '3']);
    }

    public function storeStep3(Request $request)
    {
        $request->validate([
            'db_connection' => 'required',
        ]);

        $envPath = base_path('.env');

        // Step 3: Set database Variables
        $this->setEnvValue('DB_CONNECTION', $request->db_connection);

        if ($request->db_connection !== 'sqlite') {
            $this->setEnvValue('DB_HOST', $request->db_host);
            $this->setEnvValue('DB_PORT', $request->db_port);
            $this->setEnvValue('DB_DATABASE', $request->db_database);
            $this->setEnvValue('DB_USERNAME', $request->db_username);
            $this->setEnvValue('DB_PASSWORD', $request->db_password);
        }

        $commands = config('deploywizard.final_commands');

        foreach ($commands as $command) {
            try {
                Artisan::call($command);
                // ✅ Command executed: {$command}
            } catch (\Exception $e) {
                // ❌ Command failed: {$command} - " . $e->getMessage()
            }
        }

        return redirect()->route('/');
    }

    public function setEnvValue(string $key, string $value)
    {
        $envPath = base_path('.env');

        if (file_exists($envPath)) {
            $envContent = file_get_contents($envPath);

            $pattern = "/^#?\s*{$key}=.*/m";

            if (preg_match($pattern, $envContent)) {
                $envContent = preg_replace($pattern, "{$key}={$value}", $envContent);
            } else {
                $envContent .= PHP_EOL . "{$key}={$value}";
            }

            file_put_contents($envPath, $envContent);
        }
    }
}
