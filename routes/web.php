<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\AdminAuthController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\NotificationController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\InventoryController;
use App\Http\Controllers\Backend\SaleController;
use App\Http\Controllers\Backend\CashierController;

// Admin Auth
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Admin SPA Pages (all routes return Vue SPA)
Route::prefix('admin')->middleware('auth:admin')->group(function () {
    Route::get('/dashboard', function () { return view('app'); });
    Route::get('/product', function () { return view('app'); }); // Product page
    Route::get('/product/create', function () { return view('app'); });
    Route::get('/product/{id}/edit', function () { return view('app'); });
    Route::get('/notification', function () { return view('app'); });
    Route::get('/inventory', function () { return view('app'); });
    Route::get('/purchase-order', function () { return view('app'); });
    Route::get('/report', function () { return view('app'); });
    Route::get('/cashier', function () { return view('app'); });
    Route::get('/sale', function() { return view('app'); });

    Route::get('/sale', [SaleController::class, 'index']);

    Route::get('/profile', function () { return view('app'); });
    
    Route::get('/dashboard/data', [DashboardController::class, 'index']);


    // notification routes
    Route::get('/notification/data', [NotificationController::class, 'lowStock']);
    Route::get('/notification/count', [NotificationController::class, 'lowStockCount']);
    Route::put('/notification/{id}', [NotificationController::class, 'update']);
    Route::delete('/notification/{id}', [NotificationController::class, 'destroy']);

    // Product CRUD (for Axios calls)
    Route::get('/product/data', [ProductController::class, 'index']);
    Route::post('/product', [ProductController::class, 'store']);
    Route::get('/product/{id}', [ProductController::class, 'show']);
    Route::put('/product/{id}', [ProductController::class, 'update']);
    Route::delete('/product/{id}', [ProductController::class, 'destroy']);

    Route::get('/inventory/data', [InventoryController::class, 'index']);
    Route::post('/inventory/{id}/stock-in', [InventoryController::class, 'stockIn']);
    Route::post('/inventory/{id}/stock-out', [InventoryController::class, 'stockOut']);
    Route::get('/inventory/low-stock', [InventoryController::class, 'lowStock']);

});


Route::prefix('admin')->middleware('auth:admin')->group(function () {
    Route::get('/cashier', fn () => view('app'));

    Route::get('/cashier/data', [CashierController::class, 'index']);
    Route::post('/cashier', [CashierController::class, 'store']);
    Route::put('/cashier/{id}', [CashierController::class, 'update']);
    Route::delete('/cashier/{id}', [CashierController::class, 'destroy']);
    Route::patch('/cashier/{id}/status', [CashierController::class, 'toggleStatus']);
});

// Frontend catch-all for Vue SPA
Route::get('/{any}', function () {
    return view('app');
})->where('any', '^(?!admin).*$');
