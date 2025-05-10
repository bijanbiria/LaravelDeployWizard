<?php

use Bijanbiria\LaravelDeployWizard\Http\Controllers\DeployWizardController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web']], function () {
    Route::prefix(config('deployWizard.route', 'deploy-wizard'))->group(function () {
        Route::get('/', [DeployWizardController::class, 'show'])->name('deploy-wizard.show');
        Route::post('/store-step-1', [DeployWizardController::class, 'storeStep1'])->name('deploy-wizard.step1');
        Route::post('/store-step-2', [DeployWizardController::class, 'storeStep2'])->name('deploy-wizard.step2');
        Route::post('/store-step-3', [DeployWizardController::class, 'storeStep3'])->name('deploy-wizard.step3');
    });
});