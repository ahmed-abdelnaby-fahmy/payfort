# Laravel Payfort

The Laravel Payfort package simplifies the integration of Payfort payment services with Laravel applications. It provides a straightforward solution for incorporating Payfort into your Laravel projects, offering a range of features to streamline payment processes.

## Installation

Using this package you can integrate laravel in a simple way with payfort.

```bash
    composer require aef/payfort
```

### publish the config file

```bash
    php artisan vendor:publish --provider "Payfort\Providers\PayfortServiceProvider"
```    

### contents of config file `config/payfort.php`

### Add to your `.env` file:

```bash
PAYFORT_USE_SANDBOX=true
PAYFORT_MERCHANT_IDENTIFIER=****
PAYFORT_ACCESS_CODE=*******
PAYFORT_SHA_TYPE=sha512
PAYFORT_SHA_REQUEST_PHRASE=********
PAYFORT_SHA_RESPONSE_PHRASE=**********
PAYFORT_APPLE_ACCESS_CODE=************
PAYFORT_APPLE_SHA_REQUEST_PHRASE=**********
PAYFORT_APPLE_SHA_RESPONSE_PHRASE=***********
PAYFORT_CURRENCY=SAR

```

### Purchase request (Redirection)

```php
return Payfort::purchase([
            'merchant_reference' => 'XYZ9239',
            'order_description' => 'order',
            'token_name' => "88cd5220-9584-460d-bb2d-0806f93066c1",
            'amount' => 450,
            'customer_email' => 'test@gmail.com',
]);
```

### authorize request (Redirection)

```php
return Payfort::authorize([
            'merchant_reference' => 'XYZ9239',
            'order_description' => 'order',
            'token_name' => "88cd5220-9584-460d-bb2d-0806f93066c1",
            'amount' => 450,
            'customer_email' => 'test@gmail.com',
]); 
```

### Payment maintenance operations

```php
return Payfort::payment([
            'merchant_reference' => 'XYZ9239',
            'order_description' => 'order',
            'token_name' => "88cd5220-9584-460d-bb2d-0806f93066c1",
            'amount' => 450,
            'customer_email' => 'test@gmail.com',
            'recurring_mode' => 'VARIABLE',
            'recurring_transactions_count' => 10,
]); 
```

### tokenization

```php
return Payfort::tokenization([
            'merchant_reference' => 'XYZ9239',
            'order_description' => 'order',
            'token_name' => '88cd5220-9584-460d-bb2d-0806f93066c1',
            'customer_email' => 'test@gmail.com',
            'card_number' => 4005550000000001,
            'expiry_date' => 2105,
            'card_security_code' => 123,
            'card_holder_name' => 'testcard',
]); 
```

### Apple Pay Service

```php
return Payfort::applePay([
            'merchant_reference' => 'XYZ9239',
            'order_description' => 'order',
            'token_name' => '88cd5220-9584-460d-bb2d-0806f93066c1',
            'amount' => 450,
            'customer_email' => 'test@gmail.com',
            'apple_data' => 'paymentData.data',
            'apple_signature' => 'paymentData.signature',
            'customer_ip' => '192.168.1.1',
            'phone_number' => '0123456789',
            'apple_header' => [
                  'apple_transactionId' => 'paymentData.header.transactionId',
                  'apple_ephemeralPublicKey' =>'paymentData.header.ephemeralPublicKey',
                  'apple_publicKeyHash' =>'paymentData.header.publicKeyHash'
                ],
            'apple_paymentMethod' => [
                  'apple_displayName' => 'paymentMethod.displayName',
                  'apple_network' => 'paymentMethod.network',
                  'apple_type' => 'paymentMethod.type'
                ]
]); 
```
