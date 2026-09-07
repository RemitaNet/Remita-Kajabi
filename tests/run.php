<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/src/Support/KajabiBootstrap.php';

\PaymentEngine\Kajabi\Support\KajabiBootstrap::registerAutoload();

use PaymentEngine\Kajabi\Support\CallbackUrl;
use PaymentEngine\Kajabi\Support\ChargePayloadBuilder;
use PaymentEngine\Kajabi\Support\PaymentIdentifier;
use PaymentEngine\Kajabi\Support\PaymentStatusMapper;
use PaymentEngine\Kajabi\Support\SignatureValidator;
use PaymentEngine\Kajabi\Support\TransactionVerifier;
use PaymentEngine\Kajabi\Support\WebhookUrl;

$tests = [];

$assertSame = static function (mixed $expected, mixed $actual, string $message): void {
    if ($expected !== $actual) {
        throw new RuntimeException($message . ' Expected ' . var_export($expected, true) . ', got ' . var_export($actual, true));
    }
};

$assertTrue = static function (bool $condition, string $message): void {
    if (!$condition) {
        throw new RuntimeException($message);
    }
};

$tests['callback_and_webhook_urls'] = static function () use ($assertSame): void {
    putenv('APP_URL=https://creator.example.com');
    $assertSame('https://creator.example.com/plugin/callback.php', CallbackUrl::forPlugin(), 'Unexpected Kajabi callback URL.');
    $assertSame('https://creator.example.com/plugin/webhook.php', WebhookUrl::forPlugin(), 'Unexpected Kajabi webhook URL.');
};

$tests['payment_identifier_and_payload'] = static function () use ($assertTrue, $assertSame): void {
    $identifier = PaymentIdentifier::generate('offer-42');
    $assertTrue(str_starts_with($identifier, 'KAJABI-OFFER-42-'), 'Unexpected Kajabi payment identifier format.');

    $payload = ChargePayloadBuilder::fromRequest([
        'firstName' => 'Ada',
        'lastName' => 'Lovelace',
        'email' => 'ada@example.com',
        'phoneNumber' => '08020837226',
        'currency' => 'NGN',
        'amount' => '2500',
        'offerTitle' => 'Kajabi Marketing Mastery',
    ], $identifier, 'https://creator.example.com/plugin/callback.php');

    $assertSame(250000, $payload['amount'], 'Unexpected Kajabi amount conversion.');
    $assertSame('Kajabi Marketing Mastery', $payload['narration'], 'Unexpected Kajabi narration.');
};

$tests['payment_status_mapping'] = static function () use ($assertSame): void {
    $assertSame(PaymentStatusMapper::STATUS_SUCCESS, PaymentStatusMapper::mapQueryResponse(['status' => '00']), 'Unexpected Kajabi success mapping.');
    $assertSame(PaymentStatusMapper::STATUS_PENDING, PaymentStatusMapper::mapQueryResponse(['status' => '01']), 'Unexpected Kajabi pending mapping.');
    $assertSame(PaymentStatusMapper::STATUS_FAILED, PaymentStatusMapper::mapQueryResponse(['status' => '99']), 'Unexpected Kajabi failed mapping.');
};

$tests['signature_validator'] = static function () use ($assertTrue): void {
    putenv('PAYMENT_ENGINE_OUTPOST_API_KEY=test-secret');
    $payload = '{"eventType":"payment.success"}';
    $signature = hash_hmac('sha512', $payload, 'test-secret');
    $validator = new SignatureValidator();

    $assertTrue($validator->validate($payload, $signature), 'Expected Kajabi signature validation to succeed.');
    $assertTrue(!$validator->validate($payload, 'invalid'), 'Expected Kajabi signature validation to fail.');
};

$tests['transaction_verifier_with_injected_query'] = static function () use ($assertSame): void {
    $response = TransactionVerifier::verify('https://api-checkout-qa.systemspecsng.com', 'secret', 'KAJABI-OFFER-42-ABC123', static fn (string $paymentIdentifier): array => [
        'status' => '00',
        'data' => [
            'paymentIdentifier' => $paymentIdentifier,
            'paymentState' => 'APPROVED',
        ],
    ]);

    $assertSame('KAJABI-OFFER-42-ABC123', $response['data']['paymentIdentifier'], 'Unexpected injected Kajabi verifier response.');
};

$executed = 0;

foreach ($tests as $name => $test) {
    $test();
    $executed++;
    echo "[PASS] {$name}\n";
}

echo "\nAll {$executed} Kajabi tests passed.\n";
