<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\AdminAuthController;


Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*'); // all frontend routes go to Vue


// routes/web.php

Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Protect dashboard route
Route::get('/admin/dashboard', [AdminAuthController::class, 'dashboard'])
    ->middleware('auth:admin');  // <-- only logged in admins can access


