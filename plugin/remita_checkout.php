<?php

declare(strict_types=1);

use PaymentEngine\Kajabi\Support\CallbackUrl;
use PaymentEngine\Kajabi\Support\KajabiBootstrap;

require_once dirname(__DIR__) . '/src/Support/KajabiBootstrap.php';

KajabiBootstrap::registerAutoload();

return [
    'name' => 'Remita Checkout',
    'version' => '1.0.0',
    'configuration' => [
        'base_url' => [
            'label' => 'API Base URL',
            'type' => 'text',
            'default' => 'https://api-checkout-qa.systemspecsng.com',
        ],
        'secret_key' => [
            'label' => 'Secret Key',
            'type' => 'password',
        ],
        'callback_url' => [
            'label' => 'Callback URL',
            'type' => 'text',
            'default' => CallbackUrl::forPlugin(),
        ],
    ],
];
