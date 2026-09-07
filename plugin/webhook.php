<?php

declare(strict_types=1);

use PaymentEngine\Kajabi\Support\KajabiBootstrap;
use PaymentEngine\Kajabi\Support\PaymentStatusMapper;
use PaymentEngine\Kajabi\Support\TransactionVerifier;
use PaymentEngine\Sdk\Client\PaymentEngineClient;

require_once dirname(__DIR__) . '/src/Support/KajabiBootstrap.php';

KajabiBootstrap::registerAutoload();

$engine = new PaymentEngineClient(
    (string) getenv('PAYMENT_ENGINE_BASE_URL'),
    (string) getenv('PAYMENT_ENGINE_SECRET_KEY'),
    null,
    (string) getenv('PAYMENT_ENGINE_OUTPOST_API_KEY')
);

$apiKey = (string) ($_SERVER['HTTP_X_API_KEY'] ?? '');

try {
    if (!$engine->webhooks->verifyApiKeyHeader($apiKey)) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid x-api-key header']);
        exit;
    }
} catch (Throwable $throwable) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['error' => $throwable->getMessage()]);
    exit;
}

$payload =
    file_get_contents(
        'php://input'
    );

$data =
    json_decode(
        $payload,
        true
    );

$paymentIdentifier = (string) (($data['data']['paymentIdentifier'] ?? $data['paymentIdentifier']) ?? '');

if ($paymentIdentifier === '') {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Missing payment identifier']);
    exit;
}

$response = TransactionVerifier::verify(
    (string) getenv('PAYMENT_ENGINE_BASE_URL'),
    (string) getenv('PAYMENT_ENGINE_SECRET_KEY'),
    $paymentIdentifier
);

$status = PaymentStatusMapper::mapQueryResponse($response);

http_response_code(200);

header(
    'Content-Type: application/json'
);

echo json_encode([
    'paymentIdentifier' => $paymentIdentifier,
    'status' => $status,
    'transaction' => $response['data'] ?? [],
]);
