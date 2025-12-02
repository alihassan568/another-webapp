<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Stripe API Keys
    |--------------------------------------------------------------------------
    |
    | The Stripe publishable key and secret key give you access to Stripe's
    | API. The "publishable" key is typically used in your client-side
    | code, while the "secret" key should only be used in server-side code.
    |
    */

    'key' => env('STRIPE_KEY'),
    'secret' => env('STRIPE_SECRET'),
    'webhook' => [
        'secret' => env('STRIPE_WEBHOOK_SECRET'),
        'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
    ],

    /*
    |--------------------------------------------------------------------------
    | Stripe Connect
    |--------------------------------------------------------------------------
    |
    | Here you can configure the settings related to Stripe Connect.
    |
    */

    'connect' => [
        /*
         * The platform's Stripe account ID.
         */
        'platform_account_id' => env('STRIPE_PLATFORM_ACCOUNT_ID'),

        /*
         * The default commission rate to apply if no specific rate is set.
         */
        'default_commission_rate' => 10.00, // 10%

        /*
         * The application fee percentage that will be taken from each transaction.
         */
        'application_fee_percent' => env('STRIPE_APPLICATION_FEE_PERCENT', 2.9) + 0.30, // 2.9% + $0.30
    ],

    /*
    |--------------------------------------------------------------------------
    | Currency
    |--------------------------------------------------------------------------
    |
    | This is the default currency that will be used when generating charges
    | from your application. Of course, you are welcome to use any of the
    | various world currencies that are currently supported via Stripe.
    |
    */

    'currency' => env('STRIPE_CURRENCY', 'usd'),

    /*
    |--------------------------------------------------------------------------
    | Currency Locale
    |--------------------------------------------------------------------------
    |
    | This is the default locale in which your money values are formatted in
    | for display. To utilize other locales besides the default en locale
    | verify you have the "intl" PHP extension installed on the system.
    |
    */

    'currency_locale' => env('STRIPE_CURRENCY_LOCALE', 'en_US'),

    /*
    |--------------------------------------------------------------------------
    | Payment Confirmation Notification
    |--------------------------------------------------------------------------
    |
    | If this setting is enabled, Cashier will automatically notify customers
    | whose payments require additional verification. You should listen to
    | Stripe's webhooks in order for this feature to work correctly.
    |
    */

    'payment_notification' => env('STRIPE_PAYMENT_NOTIFICATION'),

    /*
    |--------------------------------------------------------------------------
    | Invoice Paper Size
    |--------------------------------------------------------------------------
    |
    | This option is the default paper size for all invoices generated using
    | Cashier. You are free to customize this based on your needs. The
    | supported sizes are 'letter', 'legal', 'A4', etc. (in millimeters).
    |
    | Supported: 'letter', 'legal', 'A4', 'A5', 'A6'
    |
    */

    'paper' => 'letter',

    /*
    |--------------------------------------------------------------------------
    | Stripe Logger
    |--------------------------------------------------------------------------
    |
    | This setting defines the logging channel to be used by the Stripe
    | library when logging messages. You are free to use any of your
    | application's logging channels. The default is set to 'stack'.
    |
    */

    'logger' => env('STRIPE_LOGGER', 'stack'),
];
