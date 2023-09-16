<?php
return [
    /**
     * This parameter used to determine whether the request is going to be submitted to the test or production environment.
     */
    'is_sandbox' => env('PAYFORT_USE_SANDBOX', false),
    /**
     * The checkout page and messages language.
     */
    'language' => 'en',

    /**
     * The ID of the Merchant.
     */
    'merchant_identifier' => env('PAYFORT_MERCHANT_IDENTIFIER'),

    /**
     * Payfort Access code.
     */
    'access_code' => env('PAYFORT_ACCESS_CODE'),

    /**
     * Payfort Apple Access code.
     */
    'apple_access_code' => env('PAYFORT_APPLE_ACCESS_CODE'),

    /**
     * The type of the Secure Hash Algorithm (sha256/sha512).
     */
    'sha_type' => env('PAYFORT_SHA_TYPE', 'sha512'),

    /**
     * Payfort account sha request phrase
     * The phrase added to every hashed request you send to amazon Payment services.
     */
    'sha_request_phrase' => env('PAYFORT_SHA_REQUEST_PHRASE'),

    /**
     * Payfort account sha response phrase
     * The phrase added to every hashed response returned from amazon Payment services.
     */
    'sha_response_phrase' => env('PAYFORT_SHA_RESPONSE_PHRASE'),

    /**
     * Payfort account sha request phrase
     * The phrase added to every hashed request you send to amazon Payment services.
     */
    'apple_sha_request_phrase' => env('PAYFORT_APPLE_SHA_REQUEST_PHRASE'),

    /**
     * Payfort account sha response phrase
     * The phrase added to every hashed response returned from amazon Payment services.
     */
    'apple_sha_response_phrase' => env('PAYFORT_APPLE_SHA_RESPONSE_PHRASE'),

    /**
     * The currency of the transaction’s amount in ISO code 3.
     */
    'currency' => env('PAYFORT_CURRENCY', 'SAR'),

    /**
     * The URL to return after submitting Payfort forms.
     */
    'return_url' => env('PAYFORT_RETURN_URL', '/'),
    /**
     * The URL to return after submitting Payfort forms tokenization.
     */
    'return_url_tokenization' => env('PAYFORT_RETURN_TOKENIZATION_URL', '/test') ,

    /**
     * Exception Return Type JSON|view.
     */
    'exception_return_type' => 'JSON',
    /**
     * Exception Page View.
     */
    'path' => ''
];
