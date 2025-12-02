<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\StripeService;
use Illuminate\Support\Facades\Auth;

class BaseController extends Controller
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
        $this->middleware('auth:api');
    }

    /**
     * Get the authenticated user
     */
    protected function getUser()
    {
        return Auth::user();
    }

    /**
     * Calculate commission for a given amount
     */
    protected function calculateCommission($vendorId, $amountInCents)
    {
        $amount = $amountInCents / 100; // Convert to dollars for commission calculation
        $settings = \App\Models\CommissionSetting::getSettings();
        return $settings->calculateCommission($vendorId, $amount);
    }
}
