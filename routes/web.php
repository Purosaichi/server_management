<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\LicenseController;

Route::redirect('/', '/login');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth.session'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/server', [ServerController::class, 'index'])->name('server.index');
    Route::get('/server/{id}', [ServerController::class, 'show'])->name('server.detail');

    Route::get('/application', [ApplicationController::class, 'index'])->name('application.index');
    Route::get('/application/{id}', [ApplicationController::class, 'show'])->name('application.detail');

    Route::get('/domain', [DomainController::class, 'index'])->name('domain.domain');
    Route::get('/domain/{id}', [DomainController::class, 'show'])->name('domain.detail');

    Route::get('/licenses', [LicenseController::class, 'index'])->name('licenses.licenses');
    Route::get('/licenses/{id}', [LicenseController::class, 'show'])->name('licenses.detail');

    Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('maintenance.maintenance');
    Route::get('/alerts', [AlertController::class, 'index'])->name('alerts.alerts');
});