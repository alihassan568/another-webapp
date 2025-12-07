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
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\BankController;
use App\Models\User;

Route::get('run-migrate', function () {
    Artisan::call("migrate");
    return "command executed!";
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


Route::prefix('auth')->group(function () {
    Route::post('/user/signup', [AuthController::class, 'user_signup']);
    Route::post('/vender/signup', [AuthController::class, 'vender_signup']);
    Route::post('/user/login', [AuthController::class, 'user_login']);
    Route::post('/vender/login', [AuthController::class, 'vender_login']);
});

Route::post('/send-otp', [ForgotPasswordController::class, 'sendOtpEmail']);
Route::post('/verify-otp', [ForgotPasswordController::class, 'verifyOtp']);
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);

Route::get('/banks', [BankController::class, 'listBanks']);

Route::post('stripe/webhook', [StripeController::class, 'handleWebhook']);

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::get('{id}', [CategoryController::class, 'show']);
        Route::put('{id}', [CategoryController::class, 'update']);
        Route::delete('{id}', [CategoryController::class, 'destroy']);
    });

    Route::prefix('sub-categories')->group(function () {
        Route::post('/', [SubCategoryController::class, 'store']);
        Route::put('{id}', [SubCategoryController::class, 'update']);
        Route::delete('{id}', [SubCategoryController::class, 'destroy']);
    });

    Route::prefix('items')->middleware('vendor.blocked')->group(function () {
        Route::get('/', [ItemController::class, 'index']);
        Route::post('/', [ItemController::class, 'store']);
        Route::get('{id}', [ItemController::class, 'show']);
        Route::post('{id}', [ItemController::class, 'update']);
        Route::get('delete/{id}', [ItemController::class, 'destroy']);
    });

    Route::prefix('item/discount')->middleware('vendor.blocked')->group(function () {
        Route::get('/remove/{id}', [ItemDiscountController::class, 'remove_discount']);
        Route::post('/add', [ItemDiscountController::class, 'add_discount']);
    });

    Route::prefix('order')->group(function () {
        Route::post('/place', [OrderController::class, 'place_order']);
        Route::get('/venders', [OrderController::class, 'get_vender_orders'])->middleware('vendor.blocked');
        Route::get('/users', [OrderController::class, 'get_user_orders']);
        Route::post('/update/status', [OrderController::class, 'update_order_status']);
    });

    Route::prefix('wishlist')->group(function () {
        Route::post('/add', [FvtItemController::class, 'store']);
        Route::get('/', [FvtItemController::class, 'index']);
        Route::get('/remove/{id}', [FvtItemController::class, 'destroy']);
    });

    Route::prefix('profile')->group(function () {
        Route::post('/user/update', [UserController::class, 'updateUserProfile']);
        Route::post('/vender/update', [UserController::class, 'updateVenderProfile']);
        Route::get('/user', [UserController::class, 'getUserProfile']);
        Route::get('/vender', [UserController::class, 'getVenderProfile']);
    });

    Route::prefix('comment')->group(function () {
        Route::post('/store', [CommentController::class, 'store']);
        Route::post('/update/{id}', [CommentController::class, 'update']);
        Route::get('/delete/{id}', [CommentController::class, 'destroy']);
    });

    Route::prefix('commission')->group(function () {
        Route::get('/requests', [CommissionController::class, 'getItemCommissionRequest']);
        Route::post('handle/requests', [CommissionController::class, 'handleItemCommissionRequest']);
        Route::get('/default', [CommissionController::class, 'getDefaultCommission']);
        Route::post('/default', [CommissionController::class, 'updateDefaultCommission']);
    });
});

Route::prefix('get/businesses')->group(function () {
    Route::get('/', [BusinessController::class, 'getBusiness']);
    Route::get('/items', [BusinessController::class, 'getBusinessItems']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('vendor/stripe')->middleware(['auth:sanctum', 'vendor', 'vendor.blocked'])->group(function () {
        Route::post('/onboard', [StripeController::class, 'onboardVendor']);
        Route::get('/status', [StripeController::class, 'getAccountStatus']);
        Route::get('/dashboard', [StripeController::class, 'getDashboardLink']);
        Route::post('/bank-account', [StripeController::class, 'updateBankAccount']);
    });

        // Handle onboarding return URL (no vendor check since it's a callback)
        Route::get('/onboard/return', [StripeController::class, 'handleOnboardReturn'])->withoutMiddleware('vendor');

    // Order/Payment endpoints - Available to all authenticated users
    Route::prefix('orders')->group(function () {
        // Create payment intent for checkout
        Route::post('/create-payment-intent', [OrderController::class, 'createPaymentIntent']);

        // Confirm payment (handle client-side confirmation)
        Route::post('/confirm-payment', [OrderController::class, 'confirmPayment']);
    });

    // Stripe payments management (e.g., refunds)
    Route::prefix('stripe')->group(function () {
        Route::post('/refund', [StripeController::class, 'refundPayment']);
    });
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
