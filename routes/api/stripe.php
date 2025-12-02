<?php

use App\Http\Controllers\Api\StripeController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\WebhookController;
use Illuminate\Support\Facades\Route;

// Vendor onboarding routes
Route::prefix('stripe')->group(function () {
    // Get Stripe account status
    Route::get('/account/status', [StripeController::class, 'getAccountStatus']);
    
    // Start vendor onboarding
    Route::post('/onboard', [StripeController::class, 'onboard']);
    
    // Get Stripe dashboard link
    Route::get('/dashboard', [StripeController::class, 'getDashboardLink']);
    
    // Webhook handler (no auth required)
    Route::post('/webhook', [WebhookController::class, 'handleWebhook']);
});

// Payment routes
Route::prefix('payments')->group(function () {
    // Create payment intent
    Route::post('/intent', [PaymentController::class, 'createPaymentIntent']);
    
    // Confirm payment
    Route::post('/confirm', [PaymentController::class, 'confirmPayment']);
    
    // Create payout
    Route::post('/payouts', [PaymentController::class, 'createPayout']);
    
    // List payouts (for vendor)
    Route::get('/payouts', [PaymentController::class, 'listPayouts']);
});
