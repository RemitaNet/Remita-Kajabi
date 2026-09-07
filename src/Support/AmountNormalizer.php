<?php

declare(strict_types=1);

namespace PaymentEngine\Kajabi\Support;

final class AmountNormalizer
{
    public static function toKobo(string $amount): int
    {
        return (int) round(
            ((float) $amount) * 100
        );
    }
}