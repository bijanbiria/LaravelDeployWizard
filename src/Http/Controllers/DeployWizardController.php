<?php

namespace Bijanbiria\LaravelDeployWizard\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class DeployWizardController extends Controller
{
    public function show()
    {
        return view('deployWizard::deploy-wizard');
    }

    public function store(Request $request)
    {
        $request->validate([
            'db_host' => 'required',
            'db_port' => 'required',
            'db_database' => 'required',
            'db_username' => 'required',
            'db_password' => 'nullable',
        ]);

        $envPath = base_path('.env');

        file_put_contents($envPath, PHP_EOL . "DB_CONNECTION=mysql" . PHP_EOL, FILE_APPEND);
        file_put_contents($envPath, "DB_HOST={$request->db_host}" . PHP_EOL, FILE_APPEND);
        file_put_contents($envPath, "DB_PORT={$request->db_port}" . PHP_EOL, FILE_APPEND);
        file_put_contents($envPath, "DB_DATABASE={$request->db_database}" . PHP_EOL, FILE_APPEND);
        file_put_contents($envPath, "DB_USERNAME={$request->db_username}" . PHP_EOL, FILE_APPEND);
        file_put_contents($envPath, "DB_PASSWORD={$request->db_password}" . PHP_EOL, FILE_APPEND);

        try {
            DB::connection()->getPdo();
            Artisan::call('migrate');
            return redirect('/')->with('success', 'Installation successful!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Database connection failed.');
        }
    }
}