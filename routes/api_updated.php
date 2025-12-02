<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\SubCategoryController;
use App\Http\Controllers\API\ItemController;
use App\Http\Controllers\API\ItemDiscountController;
use App\Http\Controllers\API\BusinessController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\FvtItemController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ForgotPasswordController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\CommissionController;
use App\Http\Controllers\API\StripeController;
use App\Http\Controllers\API\StripeWebhookController;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Stripe Webhook Routes (No Authentication Required)
|--------------------------------------------------------------------------
*/
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::post('auth/user/signup', [AuthController::class, 'user_signup']);
Route::post('auth/vender/signup', [AuthController::class, 'vender_signup']);
Route::post('auth/user/login', [AuthController::class, 'user_login']);
Route::post('auth/vender/login', [AuthController::class, 'vender_login']);

Route::post('/send-otp', [ForgotPasswordController::class, 'sendOtpEmail']);
Route::post('/verify-otp', [ForgotPasswordController::class, 'verifyOtp']);
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);

Route::prefix('get/businesses')->group(function () {
    Route::get('/', [BusinessController::class, 'getBusiness']);
    Route::get('/items', [BusinessController::class, 'getBusinessItems']);
});

/*
|--------------------------------------------------------------------------
| Protected Routes (Require Authentication)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    
    // Categories
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::get('{id}', [CategoryController::class, 'show']);
        Route::put('{id}', [CategoryController::class, 'update']);
        Route::delete('{id}', [CategoryController::class, 'destroy']);
    });

    // Sub-categories
    Route::prefix('sub-categories')->group(function () {
        Route::post('/', [SubCategoryController::class, 'store']);
        Route::put('{id}', [SubCategoryController::class, 'update']);
        Route::delete('{id}', [SubCategoryController::class, 'destroy']);
    });

    // Items/Products
    Route::prefix('items')->group(function () {
        Route::get('/', [ItemController::class, 'index']);
        Route::post('/', [ItemController::class, 'store']);
        Route::get('{id}', [ItemController::class, 'show']);
        Route::post('{id}', [ItemController::class, 'update']);
        Route::get('delete/{id}', [ItemController::class, 'destroy']);
    });

    // Item Discounts
    Route::prefix('item/discount')->group(function () {
        Route::get('/remove/{id}', [ItemDiscountController::class, 'remove_discount']);
        Route::post('/add', [ItemDiscountController::class, 'add_discount']);
    });

    // Orders
    Route::prefix('orders')->group(function () {
        // New Stripe Connect payment flow
        Route::post('/create-payment-intent', [OrderController::class, 'createPaymentIntent']);
        Route::post('/confirm-payment', [OrderController::class, 'confirmPayment']);
        
        // Legacy order placement
        Route::post('/place', [OrderController::class, 'place_order']);
        
        // Order management
        Route::get('/venders', [OrderController::class, 'get_vender_orders']);
        Route::get('/users', [OrderController::class, 'get_user_orders']);
        Route::post('/update/status', [OrderController::class, 'update_order_status']);
    });

    // Wishlist
    Route::prefix('wishlist')->group(function () {
        Route::post('/add', [FvtItemController::class, 'store']);
        Route::get('/', [FvtItemController::class, 'index']);
        Route::get('/remove/{id}', [FvtItemController::class, 'destroy']);
    });

    // Profile
    Route::prefix('profile')->group(function () {
        Route::post('/user/update', [UserController::class, 'updateUserProfile']);
        Route::post('/vender/update', [UserController::class, 'updateVenderProfile']);
        Route::get('/user', [UserController::class, 'getUserProfile']);
        Route::get('/vender', [UserController::class, 'getVenderProfile']);
    });

    // Comments
    Route::prefix('comment')->group(function () {
        Route::post('/store', [CommentController::class, 'store']);
        Route::post('/update/{id}', [CommentController::class, 'update']);
        Route::get('/delete/{id}', [CommentController::class, 'destroy']);
    });

    // Commission Management (Admin)
    Route::prefix('commission')->group(function () {
        Route::get('/requests', [CommissionController::class, 'getItemCommissionRequest']);
        Route::post('handle/requests', [CommissionController::class, 'handleItemCommissionRequest']);
    });

    /*
    |--------------------------------------------------------------------------
    | Stripe Connect - Vendor Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('vendor/stripe')->group(function () {
        // Start vendor onboarding
        Route::post('/onboard', [StripeController::class, 'onboardVendor']);
        
        // Get account status
        Route::get('/account/status', [StripeController::class, 'getAccountStatus']);
        
        // Handle onboarding return URL
        Route::get('/onboard/return', [StripeController::class, 'handleOnboardReturn']);
        
        // Get Stripe dashboard link
        Route::get('/dashboard', [StripeController::class, 'getDashboardLink']);
    });
});

/*
|--------------------------------------------------------------------------
| Utility Routes (For Development)
|--------------------------------------------------------------------------
*/
Route::get('run-migrate', function () {
    Artisan::call("migrate");
    return "Migration command executed!";
});

Route::post('delete/user', function (Request $request) {
    $token = $request->header('X-API-TOKEN');

    if ($token == '5F42F70CA265808113862BC2BBA9848C') {
        $email = $request->email;
        User::where('email', '=', $email)->delete();

        return json_encode([
            'success' => true,
            'message' => 'user deleted successfully!'
        ]);
    }

    return json_encode([
        'success' => false,
        'message' => 'user can not be deleted!'
    ], 403);
});
