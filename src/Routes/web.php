<?php

use Bijanbiria\LaravelDeployWizard\Http\Controllers\DeployWizardController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web']], function () {
    Route::get(config('deployWizard.route', 'install'), [DeployWizardController::class, 'show']);
    Route::post(config('deployWizard.route', 'install'), [DeployWizardController::class, 'store']);
});