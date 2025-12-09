<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BusinessProfile;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BankAccountController extends Controller
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    /**
     * Setup or update vendor bank account
     * POST /api/v1/vendor/bank-account/setup
     * 
     * Body:
     * {
     *   "country": "CY",
     *   "bank_name": "Bank of Cyprus",
     *   "iban": "CY17...",
     *   "account_holder_name": "John Doe"
     * }
     */
    public function setupBankAccount(Request $request)
    {
        $validated = $request->validate([
            'country' => 'required|string|size:2',
            'bank_name' => 'nullable|string',
            'iban' => 'required|string|min:15|max:34',
            'account_holder_name' => 'required|string|max:255',
        ]);

        try {
            $user = Auth::user();

            if ($user->role !== 'business') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only vendors can access this feature'
                ], 403);
            }

            // Update or create business profile with bank details
            $business = BusinessProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'iban' => $validated['iban'],
                    'bank_name' => $validated['bank_name'],
                    'bank_title' => $validated['account_holder_name'],
                ]
            );

            // Update user's country if provided
            if (!empty($validated['country'])) {
                $user->country = $validated['country'];
                $user->save();
            }

            // If Stripe account exists, update the bank account there too
            if (!empty($user->stripe_account_id)) {
                try {
                    $this->stripeService->replaceExternalAccount(
                        $user->stripe_account_id,
                        $validated['iban']
                    );

                    return response()->json([
                        'success' => true,
                        'message' => 'Bank account setup completed and connected to Stripe',
                        'bank_details' => [
                            'country' => $validated['country'],
                            'bank_name' => $validated['bank_name'],
                            'iban_last4' => substr($validated['iban'], -4),
                            'account_holder_name' => $validated['account_holder_name'],
                        ]
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to update Stripe bank account: ' . $e->getMessage());
                    // Still save locally but return warning
                    return response()->json([
                        'success' => true,
                        'message' => 'Bank account saved, but Stripe update failed. Please try again.',
                        'warning' => $e->getMessage(),
                        'bank_details' => [
                            'country' => $validated['country'],
                            'bank_name' => $validated['bank_name'],
                            'iban_last4' => substr($validated['iban'], -4),
                            'account_holder_name' => $validated['account_holder_name'],
                        ]
                    ], 207); // 207 Multi-Status
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Bank account setup completed. You can now connect to Stripe.',
                'bank_details' => [
                    'country' => $validated['country'],
                    'bank_name' => $validated['bank_name'],
                    'iban_last4' => substr($validated['iban'], -4),
                    'account_holder_name' => $validated['account_holder_name'],
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Bank account setup error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to setup bank account',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get vendor's bank account details
     * GET /api/v1/vendor/bank-account
     */
    public function getBankAccount()
    {
        try {
            $user = Auth::user();

            if ($user->role !== 'business') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only vendors can access this feature'
                ], 403);
            }

            $business = BusinessProfile::where('user_id', $user->id)->first();

            if (!$business || empty($business->iban)) {
                return response()->json([
                    'has_bank_account' => false,
                    'message' => 'No bank account setup yet'
                ]);
            }

            return response()->json([
                'has_bank_account' => true,
                'country' => $user->country,
                'bank_name' => $business->bank_name,
                'last4' => substr($business->iban, -4),
                'iban' => $business->iban, // Full IBAN for Stripe connection
                'account_holder_name' => $business->bank_title,
                'stripe_connected' => !empty($user->stripe_account_id) && $user->stripe_status === 'complete'
            ]);

        } catch (\Exception $e) {
            Log::error('Get bank account error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to get bank account details',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validate IBAN format (basic validation)
     * POST /api/v1/vendor/bank-account/validate-iban
     */
    public function validateIban(Request $request)
    {
        $validated = $request->validate([
            'iban' => 'required|string|min:15|max:34',
            'country' => 'required|string|size:2',
        ]);

        try {
            $iban = strtoupper(str_replace(' ', '', $validated['iban']));
            $country = strtoupper($validated['country']);

            // Basic IBAN validation
            if (!preg_match('/^[A-Z]{2}[0-9]{2}[A-Z0-9]{1,30}$/', $iban)) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Invalid IBAN format'
                ]);
            }

            // Check country code in IBAN matches provided country
            $ibanCountry = substr($iban, 0, 2);
            if ($ibanCountry !== $country) {
                return response()->json([
                    'valid' => false,
                    'message' => "IBAN country code ($ibanCountry) doesn't match selected country ($country)"
                ]);
            }

            // IBAN checksum validation (mod-97 algorithm)
            if (!$this->validateIbanChecksum($iban)) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Invalid IBAN checksum'
                ]);
            }

            return response()->json([
                'valid' => true,
                'message' => 'IBAN is valid',
                'iban_last4' => substr($iban, -4)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'valid' => false,
                'message' => 'Error validating IBAN: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete vendor's bank account
     * DELETE /api/v1/vendor/bank-account/delete
     */
    public function deleteBankAccount()
    {
        try {
            $user = Auth::user();

            if ($user->role !== 'business') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only vendors can access this feature'
                ], 403);
            }

            $business = BusinessProfile::where('user_id', $user->id)->first();

            if (!$business || empty($business->iban)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No bank account found to delete'
                ], 404);
            }

            // Clear bank account details
            $business->iban = null;
            $business->bank_name = null;
            $business->bank_title = null;
            $business->save();

            Log::info('Bank account deleted', [
                'vendor_id' => $user->id,
                'vendor_name' => $user->name
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bank account removed successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Delete bank account error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete bank account',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validate IBAN checksum using mod-97 algorithm
     */
    private function validateIbanChecksum($iban)
    {
        // Move first 4 characters to end
        $rearranged = substr($iban, 4) . substr($iban, 0, 4);

        // Replace letters with numbers (A=10, B=11, ..., Z=35)
        $numeric = '';
        for ($i = 0; $i < strlen($rearranged); $i++) {
            $char = $rearranged[$i];
            if (ctype_digit($char)) {
                $numeric .= $char;
            } else {
                $numeric .= (ord($char) - ord('A') + 10);
            }
        }

        // Calculate mod 97
        $remainder = 0;
        for ($i = 0; $i < strlen($numeric); $i++) {
            $remainder = ($remainder * 10 + intval($numeric[$i])) % 97;
        }

        return $remainder === 1;
    }
}
