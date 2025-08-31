<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Customer\AuthController as CustomerAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Customer\ProductController as CustomerProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\NotificationController;


Route::get('/', function () {
    return view('welcome');
});


Route::prefix('admin')->name('admin.')->group(function () {

    Route::middleware('guest')->group(function () {
        Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AdminAuthController::class, 'login']);
        Route::get('register', [AdminAuthController::class, 'showRegistrationForm'])->name('register');
        Route::post('register', [AdminAuthController::class, 'register']);
    });


    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');


        Route::resource('products', AdminProductController::class);
        Route::get('products/import/bulk', [AdminProductController::class, 'bulkImport'])->name('products.import');
        Route::post('products/import/process', [AdminProductController::class, 'processBulkImport'])->name('products.import.process');


        Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    });
});


Route::prefix('customer')->name('customer.')->group(function () {

    Route::middleware('guest')->group(function () {
        Route::get('login', [CustomerAuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [CustomerAuthController::class, 'login']);
        Route::get('register', [CustomerAuthController::class, 'showRegistrationForm'])->name('register');
        Route::post('register', [CustomerAuthController::class, 'register']);
    });


    Route::middleware(['auth', 'customer'])->group(function () {
        Route::post('logout', [CustomerAuthController::class, 'logout'])->name('logout');
        Route::get('dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');


        Route::get('products', [CustomerProductController::class, 'index'])->name('products.index');
        Route::get('products/{product}', [CustomerProductController::class, 'show'])->name('products.show');


        Route::get('orders', [CustomerOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/create', [CustomerOrderController::class, 'create'])->name('orders.create');
        Route::post('orders', [CustomerOrderController::class, 'store'])->name('orders.store');
        Route::get('orders/{order}', [CustomerOrderController::class, 'show'])->name('orders.show');
    });
});


Route::middleware('auth')->group(function () {
    Route::post('notifications/subscribe', [NotificationController::class, 'subscribe'])->name('notifications.subscribe');
    Route::post('notifications/unsubscribe', [NotificationController::class, 'unsubscribe'])->name('notifications.unsubscribe');
});


Route::middleware('auth')->group(function () {
    Route::post('/broadcasting/auth', function () {
        return response()->json(['auth' => true]);
    });
});
