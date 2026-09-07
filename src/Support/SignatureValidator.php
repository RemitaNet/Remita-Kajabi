<?php

declare(strict_types=1);

namespace PaymentEngine\Kajabi\Support;

final class SignatureValidator
{
    public function validate(
        string $payload,
        string $signature
    ): bool {

        $expected = hash_hmac(
            'sha512',
            $payload,
            (string) (getenv('PAYMENT_ENGINE_OUTPOST_API_KEY') ?: getenv('REMITA_SECRET_KEY'))
        );

        return hash_equals(
            $expected,
            $signature
        );
    }
}
