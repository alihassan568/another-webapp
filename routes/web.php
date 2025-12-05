<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ForgotPasswordController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\CommissionController;
use App\Http\Controllers\AdminCommissionController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function() {
    try {
        // Test database connection first
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        return app(\App\Http\Controllers\HomeController::class)->index();
    } catch (\Exception $e) {
        // If database fails, return simple welcome page
        \Illuminate\Support\Facades\Log::error('Database connection failed: ' . $e->getMessage());
        return view('welcome-simple', [
            'totalApprovedItems' => 0,
            'totalCategories' => 0,
            'totalVendors' => 0
        ]);
    }
});

// Fallback route for debugging
Route::get('/test-welcome', function() {
    return view('welcome-simple', [
        'totalApprovedItems' => 0,
        'totalCategories' => 0,
        'totalVendors' => 0
    ]);
});

Route::get('/admin/commission-settings', [AdminCommissionController::class, 'index'])->name('admin.commission.settings');
Route::post('/admin/commission-settings', [AdminCommissionController::class, 'update'])->name('admin.commission.settings.update');

Route::get('/dashboard', [DashboardController::class,'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::controller(ItemController::class)->group(function () {
        Route::get('/admin/items', 'index')->name('admin.items');
        Route::get('admin/item/accept/{id}','accept')->name('admin.item.accept');
        Route::get('admin/item/reject/{id}','reject_item')->name('admin.item.reject');
        Route::post('admin/item/reject/{id}','reject')->name('admin.item.reject');
        Route::post('admin/item/filter','search')->name('admin.items.filter');
    });

    Route::controller(CommissionController::class)->group(function () {
        Route::get('admin/item/commission/{id}','create')->name('admin.item.commission');
        Route::post('admin/item/commission/{id}','store')->name('admin.item.commission');
    });
});

Route::get('admin/login',function() {
    return view('auth.admin-login');
});

Route::get('/email/verification', [ForgotPasswordController::class, 'emailVerification'])->name('email.verification');

require __DIR__.'/auth.php';
