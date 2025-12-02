<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BankController extends Controller
{
    /**
     * Return list of Stripe-compatible banks for a given country.
     *
     * GET /api/v1/banks?country=US
     */
    public function listBanks(Request $request)
    {
        $country = strtoupper($request->query('country', 'US'));

        // Bank lists are maintained in config/stripe_banks.php
        $banksConfig = config('stripe_banks', []);
        $banks = $banksConfig[$country] ?? [];

        if (empty($banks) && $country !== 'US' && isset($banksConfig['US'])) {
            $banks = $banksConfig['US'];
        }

        return response()->json([
            'success' => true,
            'country' => $country,
            'banks' => array_values($banks),
        ]);
    }
}
