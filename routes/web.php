<?php

use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DropPointController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Middleware\AdminAuthenticate;
use Illuminate\Support\Facades\Route;

// ========================
// CONSUMER ROUTES
// ========================

// Beranda / Katalog (publik)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/dashboard', fn() => redirect()->route('home'))->name('dashboard');
Route::get('/packages/{package}', [HomeController::class, 'show'])->name('packages.show');

// Keranjang & Pesanan (butuh login konsumen)
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{package}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update/{packageId}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{packageId}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/payment', [OrderController::class, 'uploadPayment'])->name('orders.upload-payment');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Breeze auth routes (register, login, logout for consumers)
require __DIR__.'/auth.php';

// ========================
// ADMIN ROUTES
// ========================

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::middleware(AdminAuthenticate::class)->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/drop-points', [DropPointController::class, 'index'])->name('drop-points.index');
        Route::get('/drop-points/create', [DropPointController::class, 'create'])->name('drop-points.create');
        Route::post('/drop-points', [DropPointController::class, 'store'])->name('drop-points.store');
        Route::get('/drop-points/{dropPoint}/edit', [DropPointController::class, 'edit'])->name('drop-points.edit');
        Route::patch('/drop-points/{dropPoint}', [DropPointController::class, 'update'])->name('drop-points.update');
        Route::patch('/drop-points/{dropPoint}/toggle', [DropPointController::class, 'toggle'])->name('drop-points.toggle');

        Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');
        Route::get('/packages/create', [PackageController::class, 'create'])->name('packages.create');
        Route::post('/packages', [PackageController::class, 'store'])->name('packages.store');
        Route::get('/packages/{package}/edit', [PackageController::class, 'edit'])->name('packages.edit');
        Route::match(['patch', 'post', 'put'], '/packages/{package}', [PackageController::class, 'update'])->name('packages.update');
        Route::delete('/packages/{package}', [PackageController::class, 'destroy'])->name('packages.destroy');

        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::post('/orders/{order}/verify-payment', [AdminOrderController::class, 'verifyPayment'])->name('orders.verify-payment');
        Route::patch('/orders/{order}/cancel', [AdminOrderController::class, 'cancel'])->name('orders.cancel');

        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    });
});
