<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\DomainController;

// Halaman Login (rute default)
Route::redirect('/', '/login');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

// Proses Login
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth.session'])->group(function () {

    // Dashboard (Beranda)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Server
    Route::get('/server', [ServerController::class, 'index'])->name('server.index');
    Route::get('/server/{id}', [ServerController::class, 'show'])->name('server.detail');

    // Application
    Route::get('/application', [ApplicationController::class, 'index'])->name('application.index');
    Route::get('/application/{id}', [ApplicationController::class, 'show'])->name('application.detail');

    // Domain
    Route::get('/domain', [DomainController::class, 'index'])->name('domain.domain');

    // Licenses (masih hardcode view nanti)
    Route::get('/licenses', function () {
        return view('pages.licenses.licenses');
    })->name('licenses.licenses');

    // Maintenance
    Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('maintenance.maintenance');

    // Alerts (masih hardcode view nanti)
    Route::get('/alerts', function () {
        return view('pages.alerts.alerts');
    })->name('alerts.alerts');
});