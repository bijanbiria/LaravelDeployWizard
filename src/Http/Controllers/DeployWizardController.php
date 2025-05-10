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

        $content = "
            DB_CONNECTION=mysql
            DB_HOST={$request->db_host}
            DB_PORT={$request->db_port}
            DB_DATABASE={$request->db_database}
            DB_USERNAME={$request->db_username}
            DB_PASSWORD={$request->db_password}
        ";

        file_put_contents($envPath, $content);

        try {
            DB::connection()->getPdo();
            Artisan::call('migrate');
            return redirect('/')->with('success', 'Installation successful!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Database connection failed.');
        }
    }
}