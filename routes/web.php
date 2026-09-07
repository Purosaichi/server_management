<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.login');
});

Route::get('/login', function () {
    return view('pages.login');
})->name('login');

Route::post('/login', function () {
    return redirect('/dashboard');
})->name('login.post');

Route::get('/dashboard', function () {
    return view('pages.dashboard');
})->name('dashboard');

// ===== ROUTE SERVER =====
Route::get('/server', function () {
    return view('pages.server.index');
})->name('server.index');

Route::get('/server/{id}', function ($id) {
    return view('pages.server.detail', ['id' => $id]);
})->name('server.detail');