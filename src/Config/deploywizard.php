<?php

return [

    // ✅ The main route where the deployment wizard starts.
    // For example, visiting `/deploy-wizard` will load the installer.
    'route' => 'deploy-wizard',


    // ✅ The route to redirect to after the deployment process is complete.
    // By default, it redirects to `/` after successful installation.
    'complete_route' => '/',

    // ✅ List of Artisan commands to be executed after successful installation.
    // These commands will run in the order they are listed here.
    'final_commands' => [
        'php artisan route:cache',
        'php artisan migrate:fresh',
        'php artisan db:seed'
    ],

    // ✅ Table name to check before starting the installation wizard
    'check_table' => 'users', // Default: users
];
