<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return view('pages.login');
})->name('login');

// Proses Login (sementara redirect ke dashboard)
Route::post('/login', function () {
    return redirect('/dashboard');
})->name('login.post');

// Halaman Dashboard (buat nanti)
Route::get('/dashboard', function () {
    return view('pages.dashboard');
})->name('dashboard');

// Route default (arahin ke login)
Route::get('/', function () {
    return view('pages.login');
})->name('home');