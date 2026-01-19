<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\AdminAuthController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\NotificationController;

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
    Route::get('/profile', function () { return view('app'); });

    Route::get('/notification/data', [NotificationController::class, 'lowStock']);
    Route::get('/notification/count', [NotificationController::class, 'lowStockCount']);
     // Notification CRUD
    Route::put('/notification/{id}', [NotificationController::class, 'update']);
    Route::delete('/notification/{id}', [NotificationController::class, 'destroy']);

    // Product CRUD (for Axios calls)
    Route::get('/product/data', [ProductController::class, 'index']);
    Route::post('/product', [ProductController::class, 'store']);
    Route::get('/product/{id}', [ProductController::class, 'show']);
    Route::put('/product/{id}', [ProductController::class, 'update']);
    Route::delete('/product/{id}', [ProductController::class, 'destroy']);
});

// Frontend catch-all for Vue SPA
Route::get('/{any}', function () {
    return view('app');
})->where('any', '^(?!admin).*$');
