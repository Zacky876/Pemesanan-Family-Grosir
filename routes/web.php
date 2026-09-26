<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// LOGIN
Route::get('/login', [AuthController::class, 'showLogin'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('guest')
    ->name('login.process');

// LOGOUT
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// DASHBOARD Admin
Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

});

// DASHBOARD Kasir
Route::middleware(['auth', 'role:kasir'])->group(function () {

    Route::get('/kasir/dashboard', function () {
        return view('kasir.dashboard');
    })->name('kasir.dashboard');

});

// DASHBOARD User
Route::middleware(['auth', 'role:user'])->group(function () {

    Route::get('/user/dashboard', function () {
        return view('user.dashboard');
    })->name('user.dashboard');

});