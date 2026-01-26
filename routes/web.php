<?php

use Illuminate\Support\Facades\Route;
use App\Models\ProductSupplier;
use App\Http\Controllers\Backend\AdminAuthController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\NotificationController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\InventoryController;
use App\Http\Controllers\Backend\SaleController;
use App\Http\Controllers\Backend\CashierController;
use App\Http\Controllers\Backend\ProfileController;
use App\Http\Controllers\Backend\ProductSupplierController;
use App\Http\Controllers\Backend\PurchaseOrderController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Frontend\GetStartController;
use App\Http\Controllers\Backend\ReportController;




// 1. Static Pages (ទំព័រដើម)
Route::get('/', [GetStartController::class, 'index']);

// 2. Admin Authentication (Login/Logout)
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// 3. Cashier Authentication (ប្រើ CashierController តែមួយ)
Route::get('/cashier/login', [CashierController::class, 'showLogin'])->name('cashier.login');
Route::post('/cashier/login', [CashierController::class, 'login']);
Route::post('/cashier/logout', [CashierController::class, 'logout'])->name('cashier.logout');

// 4. POS Route (សម្រាប់ Cashier ចូលទៅកាន់ទំព័រលក់)
Route::get('/pos', fn() => view('app'))->middleware('auth:web');

// -------------------------------------------------------------------------
// SHARED API: ដាក់នៅខាងក្រៅ prefix('admin') ដើម្បីឱ្យ Cashier (auth:web) ប្រើបាន
// -------------------------------------------------------------------------
Route::middleware(['auth:admin,web'])->group(function () {
    Route::get('/pos/product', [ProductController::class, 'index']);
    Route::post('/pos/sale', [SaleController::class, 'store']);

});

// 5. Admin SPA Pages & API (Protected by auth:admin)
Route::prefix('admin')->middleware('auth:admin')->group(function () {

    Route::get('/me', [AdminAuthController::class, 'me']);
    // Vue SPA View Routes (ត្រឡប់ទៅកាន់ view('app') ដូចគ្នាទាំងអស់)
    $spaRoutes = [
        '/dashboard', 
        '/product', 
        '/product/create',
        '/product/{id}/edit',
        '/category', 
        '/notification',
        '/inventory',
        '/purchase-order',
        '/report',
        '/supplier', 
        '/cashier',
        '/sale',
        '/profile', 
        '/profile/edit/{id}',
    ];
    foreach ($spaRoutes as $route) {
        Route::get($route, fn() => view('app'));
    }

    Route::get('/report/data', [ReportController::class, 'index']);
Route::get('/report/data', [ReportController::class, 'index']);

    // Purchase Order API
    Route::get('/purchase-order/data', [PurchaseOrderController::class, 'index']);
    Route::post('/purchase-order', [PurchaseOrderController::class, 'store']);
    Route::put('/purchase-order/{id}', [PurchaseOrderController::class, 'update']);
    Route::delete('/purchase-order/{id}', [PurchaseOrderController::class, 'destroy']);
    Route::post('/purchase-order/{id}/receive', [PurchaseOrderController::class, 'receive']);
    // Admin Profile API
    Route::get('/profile/data', [ProfileController::class, 'list']);
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

    // Category API
    Route::get('/category/data', [CategoryController::class, 'index']);  
    Route::get('/category/{id}', [CategoryController::class, 'show']);     
    Route::post('/category', [CategoryController::class, 'store']);      
    Route::put('/category/{id}', [CategoryController::class, 'update']);   
    Route::delete('/category/{id}', [CategoryController::class, 'destroy']);

    // Supplier API
    Route::get('/supplier/data', [ProductSupplierController::class, 'index']);
    Route::post('/supplier', [ProductSupplierController::class, 'store']);
    Route::put('/supplier/{id}', [ProductSupplierController::class, 'update']);
    Route::delete('/supplier/{id}', [ProductSupplierController::class, 'destroy']);

    // Inventory API
    Route::get('/inventory/data', [InventoryController::class, 'index']);
    Route::post('/inventory/{id}/stock-in', [InventoryController::class, 'stockIn']);
    Route::post('/inventory/{id}/stock-out', [InventoryController::class, 'stockOut']);
    Route::get('/inventory/low-stock', [InventoryController::class, 'lowStock']);

    // Cashier Management API
    Route::get('/cashier/data', [CashierController::class, 'index']);
    Route::post('/cashier', [CashierController::class, 'store']);
    Route::put('/cashier/{id}', [CashierController::class, 'update']);
    Route::delete('/cashier/{id}', [CashierController::class, 'destroy']);
    Route::patch('/cashier/{id}/status', [CashierController::class, 'toggleStatus']);

    // Sale API
    Route::get('/sale/data', [SaleController::class, 'index']);

    // Extra Helper Routes
    Route::get('/product-supplier', function () {
        return ProductSupplier::with('product')->get();
    });
});

// 6. Catch-all Route for Vue SPA (ត្រូវនៅក្រោមគេបំផុតជានិច្ច)
Route::get('/{any}', fn() => view('app'))->where('any', '^(?!admin).*$');