<?php

declare(strict_types=1);

namespace PaymentEngine\Kajabi\Support;

use PaymentEngine\Sdk\Client\PaymentEngineClient;

final class TransactionVerifier
{
    public static function verify(
        string $baseUrl,
        string $secretKey,
        string $paymentIdentifier,
        ?callable $queryResolver = null
    ): array {
        if ($queryResolver !== null) {
            return $queryResolver($paymentIdentifier);
        }

        $client = new PaymentEngineClient($baseUrl, $secretKey);

        return $client->payments->query($paymentIdentifier);
    }
}
