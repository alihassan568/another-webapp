<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Account;
use Stripe\AccountLink;
use Stripe\PaymentIntent;
use Stripe\Transfer;
use Stripe\Webhook;
use Stripe\Balance;
use Stripe\Payout;
use Stripe\Refund;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Exception\UnexpectedValueException;
use Stripe\Exception\ApiErrorException;
use Illuminate\Support\Facades\Log;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Create a Stripe Custom Connect account
     */
    public function createCustomAccount($email, $country = 'US')
    {
        Log::info('StripeService: Creating Custom Account', ['email' => $email, 'country' => $country]);
        try {
            return Account::create([
                'type' => 'custom',
                'country' => $country,
                'email' => $email,
                'capabilities' => [
                    'card_payments' => ['requested' => true],
                    'transfers' => ['requested' => true],
                ],
                'business_type' => 'individual',
            ]);
        } catch (ApiErrorException $e) {
            Log::error('Stripe Custom Account Creation Failed', [
                'email' => $email,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Update a Stripe Custom Connect account
     */
    public function updateCustomAccount($accountId, $data)
    {
        Log::info('StripeService: Updating Custom Account', ['account_id' => $accountId]);
        try {
            return Account::update($accountId, $data);
        } catch (ApiErrorException $e) {
            Log::error('Stripe Account Update Failed', [
                'account_id' => $accountId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Add external account (Bank Account) to Custom Account
     */
    public function addExternalAccount($accountId, $iban)
    {
        Log::info('StripeService: Adding External Account', ['account_id' => $accountId, 'iban_masked' => substr($iban, -4)]);
        try {
            $account = Account::retrieve($accountId);
            return $account->external_accounts->create([
                'external_account' => [
                    'object' => 'bank_account',
                    'country' => $account->country,
                    'currency' => 'eur', // Assuming EUR for Cyprus/IBAN
                    'account_number' => $iban,
                ],
            ]);
        } catch (ApiErrorException $e) {
            Log::error('Stripe Add External Account Failed', [
                'account_id' => $accountId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Replace external account (Delete old, add new)
     */
    public function replaceExternalAccount($accountId, $iban)
    {
        try {
            $account = Account::retrieve($accountId);
            
            // Delete existing bank accounts
            if (!empty($account->external_accounts->data)) {
                foreach ($account->external_accounts->data as $externalAccount) {
                    if ($externalAccount->object === 'bank_account') {
                        $account->external_accounts->retrieve($externalAccount->id)->delete();
                    }
                }
            }

            // Add new one
            return $this->addExternalAccount($accountId, $iban);
            
        } catch (ApiErrorException $e) {
            Log::error('Stripe Replace External Account Failed', [
                'account_id' => $accountId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Agree to Stripe TOS on behalf of the user
     */
    public function agreeToTerms($accountId, $ipAddress)
    {
        try {
            return Account::update($accountId, [
                'tos_acceptance' => [
                    'date' => time(),
                    'ip' => $ipAddress,
                ],
            ]);
        } catch (ApiErrorException $e) {
            Log::error('Stripe TOS Acceptance Failed', [
                'account_id' => $accountId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Create account link for onboarding
     */
    public function createAccountLink($accountId, $refreshUrl, $returnUrl)
    {
        try {
            return AccountLink::create([
                'account' => $accountId,
                'refresh_url' => $refreshUrl,
                'return_url' => $returnUrl,
                'type' => 'account_onboarding',
            ]);
        } catch (ApiErrorException $e) {
            Log::error('Stripe Account Link Creation Failed', [
                'account_id' => $accountId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Create payment intent with destination charge
     */
    public function createPaymentIntent($amount, $currency, $applicationFee, $destination, array $metadata = [])
    {
        try {
            Log::info('[StripeService] Creating PaymentIntent', [
                'amount' => $amount,
                'currency' => $currency,
                'application_fee_amount' => $applicationFee,
                'destination' => $destination,
                'metadata' => $metadata
            ]);
            $intent = PaymentIntent::create([
                'amount' => $amount,
                'currency' => $currency,
                'application_fee_amount' => $applicationFee,
                'transfer_data' => [
                    'destination' => $destination,
                ],
                'metadata' => $metadata,
                'automatic_payment_methods' => [
                    'enabled' => true,
                    'allow_redirects' => 'never',
                ],
            ]);
            Log::info('[StripeService] PaymentIntent created', ['payment_intent_id' => $intent->id]);
            return $intent;
        } catch (ApiErrorException $e) {
            Log::error('[StripeService] Payment Intent Creation Failed', [
                'amount' => $amount,
                'destination' => $destination,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'metadata' => $metadata
            ]);
            throw $e;
        }
    }

    /**
     * Create a direct transfer to connected account
     */
    public function createTransfer($amount, $currency, $destination, $description = 'Transfer to vendor')
    {
        try {
            return Transfer::create([
                'amount' => $amount,
                'currency' => $currency,
                'destination' => $destination,
                'description' => $description,
            ]);
        } catch (ApiErrorException $e) {
            Log::error('Stripe Transfer Creation Failed', [
                'amount' => $amount,
                'destination' => $destination,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Handle webhook signature verification
     */
    public function handleWebhook($payload, $sigHeader)
    {
        $endpointSecret = config('services.stripe.webhook.secret');
        
        try {
            return Webhook::constructEvent(
                $payload, $sigHeader, $endpointSecret
            );
        } catch (UnexpectedValueException $e) {
            Log::error('Invalid webhook payload', ['error' => $e->getMessage()]);
            throw $e;
        } catch (SignatureVerificationException $e) {
            Log::error('Invalid webhook signature', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Get account balance
     */
    public function getAccountBalance($accountId)
    {
        try {
            return Balance::retrieve(['stripe_account' => $accountId]);
        } catch (ApiErrorException $e) {
            Log::error('Stripe Get Balance Failed', [
                'account_id' => $accountId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Create payout to connected account's bank
     */
    public function createPayout($accountId, $amount, $currency = 'usd')
    {
        try {
            return Payout::create([
                'amount' => $amount,
                'currency' => $currency,
            ], [
                'stripe_account' => $accountId,
            ]);
        } catch (ApiErrorException $e) {
            Log::error('Stripe Payout Creation Failed', [
                'account_id' => $accountId,
                'amount' => $amount,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Create a refund
     */
    public function createRefund($paymentIntentId, $amount = null)
    {
        try {
            $params = ['payment_intent' => $paymentIntentId];
            if ($amount) {
                $params['amount'] = $amount;
            }
            
            return Refund::create($params);
        } catch (ApiErrorException $e) {
            Log::error('Stripe Refund Creation Failed', [
                'payment_intent_id' => $paymentIntentId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Retrieve account details
     */
    public function getAccount($accountId)
    {
        try {
            return Account::retrieve($accountId);
        } catch (ApiErrorException $e) {
            Log::error('Stripe Get Account Failed', [
                'account_id' => $accountId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Create login link for connected account dashboard
     */
    public function createLoginLink($accountId)
    {
        try {
            return Account::createLoginLink($accountId);
        } catch (ApiErrorException $e) {
            Log::error('Stripe Login Link Creation Failed', [
                'account_id' => $accountId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Verify account can accept charges
     */
    public function canAccountAcceptCharges($accountId): bool
    {
        try {
            $account = $this->getAccount($accountId);
            return $account->charges_enabled && $account->details_submitted;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check if account has payouts enabled
     */
    public function hasPayoutsEnabled($accountId): bool
    {
        try {
            $account = $this->getAccount($accountId);
            return $account->payouts_enabled;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get account verification requirements
     */
    public function getAccountRequirements($accountId): array
    {
        try {
            $account = $this->getAccount($accountId);
            return [
                'currently_due' => $account->requirements->currently_due ?? [],
                'eventually_due' => $account->requirements->eventually_due ?? [],
                'past_due' => $account->requirements->past_due ?? [],
            ];
        } catch (\Exception $e) {
            Log::error('Failed to get account requirements', [
                'account_id' => $accountId,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    /**
     * Test Stripe connection
     */
    public function testConnection(): bool
    {
        try {
            Balance::retrieve();
            return true;
        } catch (\Exception $e) {
            Log::error('Stripe connection test failed', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
