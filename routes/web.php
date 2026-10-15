<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\ServerNoteController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\LicenseController;
use App\Http\Controllers\NetworkDeviceController;

Route::redirect('/', '/login');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth.session'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/server', [ServerController::class, 'index'])->name('server.index');
    Route::get('/server/create', [ServerController::class, 'create'])->name('server.create');
    Route::post('/server', [ServerController::class, 'store'])->name('server.store');
    Route::get('/server/{id}/edit', [ServerController::class, 'edit'])->name('server.edit');
    Route::put('/server/{id}', [ServerController::class, 'update'])->name('server.update');
    Route::put('/server/{id}/network', [ServerController::class, 'updateNetwork'])->name('server.update-network');
    Route::delete('/server/{id}', [ServerController::class, 'destroy'])->name('server.destroy');
    Route::get('/server/{id}', [ServerController::class, 'show'])->name('server.detail');

    Route::post('/server-notes', [ServerNoteController::class, 'store'])->name('server-notes.store');
    Route::put('/server-notes/{id}', [ServerNoteController::class, 'update'])->name('server-notes.update');
    Route::delete('/server-notes/{id}', [ServerNoteController::class, 'destroy'])->name('server-notes.destroy');

    Route::get('/application', [ApplicationController::class, 'index'])->name('application.index');
    Route::get('/application/create', [ApplicationController::class, 'create'])->name('application.create');
    Route::post('/application', [ApplicationController::class, 'store'])->name('application.store');
    Route::get('/application/{id}/edit', [ApplicationController::class, 'edit'])->name('application.edit');
    Route::put('/application/{id}', [ApplicationController::class, 'update'])->name('application.update');
    Route::put('/application/{id}/pic', [ApplicationController::class, 'updatePic'])->name('application.update-pic');
    Route::post('/application/pic', [ApplicationController::class, 'storePic'])->name('application.store-pic');
    Route::delete('/application/{id}', [ApplicationController::class, 'destroy'])->name('application.destroy');
    Route::get('/application/{id}', [ApplicationController::class, 'show'])->name('application.detail');

    Route::get('/domain', [DomainController::class, 'index'])->name('domain.domain');
    Route::get('/domain/create', [DomainController::class, 'create'])->name('domain.create');
    Route::post('/domain', [DomainController::class, 'store'])->name('domain.store');
    Route::get('/domain/{id}', [DomainController::class, 'show'])->name('domain.detail');
    Route::get('/domain/{id}/edit', [DomainController::class, 'edit'])->name('domain.edit');
    Route::put('/domain/{id}', [DomainController::class, 'update'])->name('domain.update');
    Route::delete('/domain/{id}', [DomainController::class, 'destroy'])->name('domain.destroy');

    Route::get('/licenses', [LicenseController::class, 'index'])->name('licenses.licenses');
    Route::get('/licenses/{id}', [LicenseController::class, 'show'])->name('licenses.detail');

    Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('maintenance.maintenance');
    Route::post('/maintenance', [MaintenanceController::class, 'store'])->name('maintenance.store');
    Route::delete('/maintenance/clear', [MaintenanceController::class, 'clear'])->name('maintenance.clear');
    Route::put('/maintenance/{id}', [MaintenanceController::class, 'update'])->name('maintenance.update');
    Route::get('/alerts', [AlertController::class, 'index'])->name('alerts.alerts');

    Route::prefix('network-device')->name('network-device.')->group(function () {
        Route::get('/router', [NetworkDeviceController::class, 'router'])->name('router');
        Route::get('/switch', [NetworkDeviceController::class, 'switch'])->name('switch');
        Route::get('/router/{id}', [NetworkDeviceController::class, 'routerDetail'])->name('router-detail');
        Route::get('/switch/{id}', [NetworkDeviceController::class, 'switchDetail'])->name('switch-detail');
    });
});