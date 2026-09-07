<?php

declare(strict_types=1);

use PaymentEngine\Kajabi\Support\KajabiBootstrap;
use PaymentEngine\Kajabi\Support\PaymentStatusMapper;
use PaymentEngine\Kajabi\Support\TransactionVerifier;

require_once dirname(__DIR__) . '/src/Support/KajabiBootstrap.php';

KajabiBootstrap::registerAutoload();

$paymentIdentifier =
    (string) ($_GET['paymentIdentifier'] ?? '');

if ($paymentIdentifier === '') {

    http_response_code(400);

    exit('Missing payment identifier');
}

$response =
    TransactionVerifier::verify(
        (string) getenv('PAYMENT_ENGINE_BASE_URL'),
        (string) getenv('PAYMENT_ENGINE_SECRET_KEY'),
        $paymentIdentifier
    );

$status =
    PaymentStatusMapper::mapQueryResponse(
        $response
    );

$payload = [
    'paymentIdentifier' => $paymentIdentifier,
    'status' => $status,
    'transaction' => $response['data'] ?? [],
];

$accept = (string) ($_SERVER['HTTP_ACCEPT'] ?? '');

if (str_contains($accept, 'application/json')) {
    header('Content-Type: application/json');
    echo json_encode($payload);
    exit;
}

$returnUrl = rtrim((string) (getenv('APP_URL') ?: ''), '/');

header('Content-Type: text/html; charset=UTF-8');
echo '<!DOCTYPE html><html lang="en"><body><h1>Payment ' . htmlspecialchars($status, ENT_QUOTES, 'UTF-8') . '</h1><p>Reference: ' . htmlspecialchars($paymentIdentifier, ENT_QUOTES, 'UTF-8') . '</p>' . ($returnUrl !== '' ? '<p><a href="' . htmlspecialchars($returnUrl, ENT_QUOTES, 'UTF-8') . '">Back to Kajabi</a></p>' : '') . '</body></html>';
