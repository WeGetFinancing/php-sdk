<div align="center">

![WeGetFinancing](https://wegetfinancing.com/static/logo-5c2c832d18c270cfb7944df0504eb609.svg)
# PHP-SDK
[![Maintainability](https://api.codeclimate.com/v1/badges/ace0717d4ceb908017df/maintainability)](https://codeclimate.com/github/WeGetFinancing/php-sdk/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/ace0717d4ceb908017df/test_coverage)](https://codeclimate.com/github/WeGetFinancing/php-sdk/test_coverage)
[![Quality Assurance CI](https://github.com/WeGetFinancing/php-sdk/actions/workflows/qa.yml/badge.svg)](https://github.com/WeGetFinancing/php-sdk/actions/workflows/qa.yml)
[![Test CI](https://github.com/WeGetFinancing/php-sdk/actions/workflows/test.yml/badge.svg)](https://github.com/WeGetFinancing/php-sdk/actions/workflows/test.yml)
[![PHPStan](https://img.shields.io/badge/PHPStan-Level%208-brightgreen.svg?style=flat&logo=php)](https://shields.io/#/)

</div>

The WeGetFinancing SDK for PHP makes it easy for developers to access the WeGetFinancing API in their PHP code and integrate our payment gateway in their e-commerce, CRM, ERP, and any related web applications.
You can get started in minutes by installing the SDK through Composer or by downloading a single zip from our latest release.

## Index

1. [Minimum Requirements](#Minimum-Requirements)
2. [Install](#Install)
3. [How to use](#How-to-use)

## Minimum Requirements

To run the SDK, your system will need to meet the minimum requirements of having PHP >= 7.4 with ext-json.

## Install

Using Composer is the recommended way to install the  WeGetFinancing PHP-SDK.
The SDK is available via Packagist under the `wegetfinancing/php-sdk` package. 
If Composer is installed globally on your system, you can run the following in the base directory of your project to add the SDK as a dependency:
```bash
composer require `wegetfinancing/php-sdk`
```

## How to use

1. Require the composer autoload and the packages.
    ```php
    <?php
    
    require 'vendor/autoload.php';
    
    use WeGetFinancing\SDK\Client;
    use WeGetFinancing\SDK\Entity\Request\AuthRequestEntity;
    use WeGetFinancing\SDK\Entity\Request\LoanRequestEntity;
    ```
2. Make an AuthResponseEntity and use it to initialise the client
    ```php
    $auth = AuthRequestEntity::make([
        'username' => '***',
        'password'  => '***',
        'merchantId' => '***',
        'url' => 'https://***'
    ]);
    
    $client = Client::Make($auth);
    ```
3. Make an LoanRequestEntity, for more information about the parameter to use, please refer to the [official documentation](https://docs.wegetfinancing.com/backend-integration.html#wegetfinancing-api).
    ```php
    $request = LoanRequestEntity::make([
        'first_name' => '***',
        'last_name' => '***',
        'shipping_amount' => 1.2,
        'version' => '1.9',
        'email' => '***@example.com',
        'phone' => '0123456789',
        'merchant_transaction_id' => '***',
        'success_url' => 'https://yoururl.com/successurl',   // this is facultative
        'failure_url' => 'https://yoururl.com/failureurl',   // this is facultative
        'postback_url' => 'https://yoururl.com/postbackurl', // this is facultative
        'billing_address' => [
            'street1' => '***',
            'city' => '***',
            'state' => '***',
            'zipcode' => '***',
        ],
        'shipping_address' => [
            'street1' => '***',
            'city' => '***',
            'state' => '***',
            'zipcode' => '***',
        ],
        'cart_items' => [
            [
                'sku' => '***',
                'display_name' => '***',
                'unit_price' => '***',
                'quantity' => ***,
                'unit_tax' => **.*,
                'category' => '***',      // Facultative
            ]
        ]
    ]);
    ```
4. Use the LoanRequestEntity to make an application and receive a LoanResponseEntity with the result of it. 
    ```php
    $response = $client->requestNewLoan($request);
    ```
