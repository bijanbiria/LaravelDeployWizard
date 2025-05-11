<?php

namespace Bijanbiria\LaravelDeployWizard\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;

class DeployWizardController extends Controller
{
    /**
     * Display the deployment wizard view with default configuration values.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function show(Request $request): View
    {
        // Render the view and pass current configuration values to the form
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

    /**
     * Handle the submission of Step 1 form, setting application name and URL.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function storeStep1(Request $request): RedirectResponse
    {
        // Validate required fields for Step 1
        $request->validate([
            'app_name' => 'required',
            'app_url'  => 'required',
        ]);

        // Step 1: Set Base Variables
        $this->setEnvValue('APP_NAME', $request->app_name);
        $this->setEnvValue('APP_URL', $request->app_url);
        $this->setEnvValue('APP_ENV', 'production');
        $this->setEnvValue('APP_DEBUG', 'true');

        // Redirect to the next step (Step 2)
        return redirect()->route('deploy-wizard.show', ['step' => '2']);
    }

    /**
     * Handle the submission of Step 2 form, setting locale and faker configuration.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function storeStep2(Request $request): RedirectResponse
    {
        // Validate required fields for Step 2
        $request->validate([
            'app_locale'          => 'required',
            'app_fallback_locale' => 'required',
            'app_faker_locale'    => 'required',
        ]);

        // Step 2: Set Locale Variables
        $this->setEnvValue('APP_LOCALE', $request->app_locale);
        $this->setEnvValue('APP_FALLBACK_LOCALE', $request->app_fallback_locale);
        $this->setEnvValue('APP_FAKER_LOCALE', $request->app_faker_locale);

        // Redirect to the next step (Step 3)
        return redirect()->route('deploy-wizard.show', ['step' => '3']);
    }

    /**
     * Handle the submission of Step 3 form, setting database configurations
     * and executing final Artisan commands.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function storeStep3(Request $request): RedirectResponse
    {
        // Validate required fields for Step 3
        $request->validate([
            'db_connection' => 'required',
        ]);

        // Step 3: Set Database Variables
        $this->setEnvValue('DB_CONNECTION', $request->db_connection);

        // If the database is not SQLite, set additional configurations
        if ($request->db_connection !== 'sqlite') {
            $this->setEnvValue('DB_HOST', $request->db_host);
            $this->setEnvValue('DB_PORT', $request->db_port);
            $this->setEnvValue('DB_DATABASE', $request->db_database);
            $this->setEnvValue('DB_USERNAME', $request->db_username);
            $this->setEnvValue('DB_PASSWORD', $request->db_password);
        }

        // Redirect to the complete route defined in the configuration
        return redirect()->route('deploy-wizard.show', ['step' => '4']);
    }

    public function storeStep4(Request $request): RedirectResponse
    {
        // ✅ Execute final Artisan commands defined in the configuration
        $commands = config('deploywizard.final_commands');

        foreach ($commands as $command) {
            // Remove 'php artisan ' if it exists at the beginning of the command
            $command = str_starts_with($command, 'php artisan ') ? substr($command, 12) : $command;

            try {
                Artisan::call($command);
                // ✅ Command executed: {$command}
            } catch (\Exception $e) {
                // ❌ Command failed: {$command} - " . $e->getMessage()
            }
        }

        // Redirect to the complete route defined in the configuration
        return redirect()->to(config('deploywizard.complete_route', '/'));
    }

    /**
     * Update or add a key-value pair in the .env file.
     *
     * @param string $key
     * @param string $value
     * @return void
     */
    public function setEnvValue(string $key, string $value): void
    {
        $envPath = base_path('.env');

        if (file_exists($envPath)) {
            $envContent = file_get_contents($envPath);

            // Regex pattern to find the key, even if it's commented (#)
            $pattern = "/^#?\s*{$key}=.*/m";

            if (preg_match($pattern, $envContent)) {
                // ✅ If the key exists (even as a comment), replace its value
                $envContent = preg_replace($pattern, "{$key}={$value}", $envContent);
            } else {
                // ✅ If the key doesn't exist, append it to the end of the file
                $envContent .= PHP_EOL . "{$key}={$value}";
            }

            // ✅ Write the new content back to .env
            file_put_contents($envPath, $envContent);
        }
    }
}
