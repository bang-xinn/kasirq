<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // POS
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::get('/pos/products', [PosController::class, 'getProducts'])->name('pos.products');
    Route::post('/pos/payment', [PosController::class, 'processPayment'])->name('pos.payment');

    // Transactions
    Route::resource('transactions', TransactionController::class)->only(['index', 'show']);

    // Admin Only
    Route::middleware('admin')->group(function () {
        Route::resource('categories', CategoryController::class)->except(['show']);
        
        Route::get('/products/generate-sku', [ProductController::class, 'generateSku'])->name('products.generate-sku');
        Route::resource('products', ProductController::class)->except(['show']);
        Route::resource('users', UserController::class)->except(['show']);
        
        Route::get('/settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
    });
});
