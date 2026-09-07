<?php

declare(strict_types=1);

namespace PaymentEngine\Kajabi\Support;

final class PaymentIdentifier
{
    public static function generate(
        string $offerId
    ): string {

        return sprintf(
            'KAJABI-%s-%s',
            strtoupper($offerId),
            strtoupper(bin2hex(random_bytes(6)))
        );
    }
}