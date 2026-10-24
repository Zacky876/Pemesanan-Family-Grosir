<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\CustomerController;

use App\Http\Controllers\CustomerProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerOrderController;

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentVerificationController;

Route::get('/', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    return match (Auth::user()->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'kasir' => redirect()->route('kasir.dashboard'),
        'user' => redirect()->route('user.dashboard'),
        default => abort(403, 'Role tidak valid.'),
    };
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

    Route::prefix('admin/customers')->name('admin.customers.')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])
            ->name('index');

        Route::get('/create', [CustomerController::class, 'create'])
            ->name('create');

        Route::post('/', [CustomerController::class, 'store'])
            ->name('store');

        Route::get('/{customer}', [CustomerController::class, 'show'])
            ->name('show');

        Route::get('/{customer}/edit', [CustomerController::class, 'edit'])
            ->name('edit');

        Route::put('/{customer}', [CustomerController::class, 'update'])
            ->name('update');

        Route::patch('/{customer}/status', [CustomerController::class, 'toggleStatus'])
            ->name('toggle-status');

        Route::delete('/{customer}', [CustomerController::class, 'destroy'])
            ->name('destroy');
    });

    Route::middleware(['auth'])->prefix('admin/payments')->name('admin.payments.')->group(function () {
        Route::get('/', [PaymentVerificationController::class, 'index'])
            ->name('index');

        Route::get('/{transaction}/proof', [PaymentVerificationController::class, 'proof'])
            ->name('proof');

        Route::post('/{transaction}/confirm', [PaymentVerificationController::class, 'confirm'])
            ->name('confirm');

        Route::post('/{transaction}/fail', [PaymentVerificationController::class, 'fail'])
            ->name('fail');
    });
});

// DASHBOARD Kasir
Route::middleware(['auth', 'role:kasir'])->group(function () {

    Route::get('/kasir/dashboard', function () {
        return view('kasir.dashboard');
    })->name('kasir.dashboard');

});

// DASHBOARD User
Route::middleware(['auth', 'role:user'])->group(function () {

    Route::get('/user/dashboard', [DashboardController::class, 'user'])
        ->name('user.dashboard');

    Route::prefix('user/products')->name('user.products.')->group(function () {
        Route::get('/', [CustomerProductController::class, 'index'])
            ->name('index');

        Route::get('/{product}', [CustomerProductController::class, 'show'])
            ->name('show');
    });
    Route::prefix('user/cart')->name('user.cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])
            ->name('index');
        Route::post('/{product}', [CartController::class, 'add'])
            ->name('add');
        Route::patch('/{product}', [CartController::class, 'update'])
            ->name('update');
        Route::delete('/{product}', [CartController::class, 'remove'])
            ->name('remove');
    });
    Route::prefix('user/checkout')->name('user.checkout.')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])
        ->name('index');
        Route::post('/', [CheckoutController::class, 'store'])
        ->name('store');
        Route::get('/success/{transaction}', [CheckoutController::class, 'success'])
        ->name('success');
    });
    Route::prefix('user/orders')->name('user.orders.')->group(function () {
        Route::get('/', [CustomerOrderController::class, 'index'])
            ->name('index');
        Route::get('/{transaction}', [CustomerOrderController::class, 'show'])
            ->name('show');
    });
    Route::middleware(['auth'])->group(function () {
        Route::get('/user/payment/{transaction}', [PaymentController::class, 'show'])
            ->name('user.payment.show');

        Route::post('/user/payment/{transaction}/proof', [PaymentController::class, 'uploadProof'])
            ->name('user.payment.proof');
    });

});