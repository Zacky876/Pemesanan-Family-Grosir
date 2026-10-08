<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;

Route::get('/', function () {
    return redirect()->route('login');
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

    Route::resource ('/admin/categories', CategoryController::class)
        ->names ('admin.categories');

    Route::resource('/admin/products', ProductController::class)
        ->names('admin.products');

    Route::get('/admin/stock', [StockController::class, 'index'])
        ->name('admin.stock.index');

    Route::get('/admin/stock/{product}/create', [StockController::class, 'create'])
        ->name('admin.stock.create');

    Route::post('/admin/stock/{product}', [StockController::class, 'store'])
        ->name('admin.stock.store');

    Route::get('/admin/stock/{product}/history', [StockController::class, 'history'])
        ->name('admin.stock.history');

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