<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ForgotPasswordController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\CommissionController;
use App\Http\Controllers\AdminCommissionController;
use App\Http\Controllers\Admin\AdminRoleController;
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
        Route::get('admin/item/reject/{id}','reject_item')->name('admin.item.reject.form');
        Route::post('admin/item/reject/{id}','reject')->name('admin.item.reject');
        Route::post('admin/item/filter','search')->name('admin.items.filter');
    });

    Route::controller(CommissionController::class)->group(function () {
        Route::get('admin/item/commission/{id}','create')->name('admin.item.commission');
        Route::post('admin/item/commission/{id}','store')->name('admin.item.commission');
    });

    // Admin Role Management Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('roles', AdminRoleController::class);
    });

    // Invite Routes
    Route::prefix('admin/invites')->name('invite.')->group(function () {
        Route::get('/', [\App\Http\Controllers\InviteController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\InviteController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\InviteController::class, 'store'])->name('store');
    });

    // Vendor Management Routes
    Route::prefix('admin/vendors')->name('admin.vendors.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\VendorController::class, 'index'])->name('index');
        Route::get('/{vendor}', [\App\Http\Controllers\Admin\VendorController::class, 'show'])->name('show');
        Route::patch('/{vendor}/block', [\App\Http\Controllers\Admin\VendorController::class, 'block'])->name('block');
        Route::patch('/{vendor}/unblock', [\App\Http\Controllers\Admin\VendorController::class, 'unblock'])->name('unblock');
    });
});

// Public invite routes (no auth required)
Route::get('/invite/accept/{token}', [\App\Http\Controllers\InviteController::class, 'accept'])->name('invite.accept');
Route::post('/invite/accept/{token}', [\App\Http\Controllers\InviteController::class, 'confirmAccept'])->name('invite.confirm');
Route::post('/invite/cancel/{token}', [\App\Http\Controllers\InviteController::class, 'cancel'])->name('invite.cancel');

Route::get('admin/login',function() {
    return view('auth.admin-login');
});

Route::get('/email/verification', [ForgotPasswordController::class, 'emailVerification'])->name('email.verification');

require __DIR__.'/auth.php';
