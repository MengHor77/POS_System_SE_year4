<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\AdminAuthController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\NotificationController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\InventoryController;
use App\Http\Controllers\Backend\SaleController;
use App\Http\Controllers\Backend\CashierController;
use App\Http\Controllers\Backend\ProfileController;

// ----------------------
// Admin Authentication
// ----------------------
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// ----------------------
// Admin SPA Pages (Vue)
// ----------------------
Route::prefix('admin')->middleware('auth:admin')->group(function () {
    
    // Vue SPA routes (return the same app view)
    $spaRoutes = [
        '/dashboard',
        '/product',
        '/product/create',
        '/product/{id}/edit',
        '/notification',
        '/inventory',
        '/purchase-order',
        '/report',
        '/supplier',
        '/cashier',
        '/sale',      
        '/profile',
        '/profile/edit/{id}', // ✅ add edit page

    ];

    foreach ($spaRoutes as $route) {
        Route::get($route, fn() => view('app'));
    }

  // Admin Profile Table
    Route::get('/profile/data', [ProfileController::class, 'list']);
    
    // Single Admin for Edit
    Route::get('/profile/{id}', [ProfileController::class, 'show']);
    Route::post('/profile/{id}', [ProfileController::class, 'update']);
    // Dashboard Data API
    Route::get('/dashboard/data', [DashboardController::class, 'index']);

    // Notification API
    Route::get('/notification/data', [NotificationController::class, 'lowStock']);
    Route::get('/notification/count', [NotificationController::class, 'lowStockCount']);
    Route::put('/notification/{id}', [NotificationController::class, 'update']);
    Route::delete('/notification/{id}', [NotificationController::class, 'destroy']);

    // Product CRUD API
    Route::get('/product/data', [ProductController::class, 'index']);
    Route::post('/product', [ProductController::class, 'store']);
    Route::get('/product/{id}', [ProductController::class, 'show']);
    Route::put('/product/{id}', [ProductController::class, 'update']);
    Route::delete('/product/{id}', [ProductController::class, 'destroy']);

    Route::get('/supplier/data', [ProductSupplierController::class, 'index']);
    Route::post('/supplier', [ProductSupplierController::class, 'store']);
    Route::put('/supplier/{id}', [ProductSupplierController::class, 'update']);
    Route::delete('/supplier/{id}', [ProductSupplierController::class, 'destroy']);

    // Inventory API
    Route::get('/inventory/data', [InventoryController::class, 'index']);
    Route::post('/inventory/{id}/stock-in', [InventoryController::class, 'stockIn']);
    Route::post('/inventory/{id}/stock-out', [InventoryController::class, 'stockOut']);
    Route::get('/inventory/low-stock', [InventoryController::class, 'lowStock']);

    // Cashier API
    Route::get('/cashier/data', [CashierController::class, 'index']);
    Route::post('/cashier', [CashierController::class, 'store']);
    Route::put('/cashier/{id}', [CashierController::class, 'update']);
    Route::delete('/cashier/{id}', [CashierController::class, 'destroy']);
    Route::patch('/cashier/{id}/status', [CashierController::class, 'toggleStatus']);

    // Sale API
    Route::get('/sale/data', [SaleController::class, 'index']); // <- API endpoint for Vue
});

// ----------------------
// Frontend catch-all for Vue SPA
// ----------------------
Route::get('/{any}', fn() => view('app'))->where('any', '^(?!admin).*$');
