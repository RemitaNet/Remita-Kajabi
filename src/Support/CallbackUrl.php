<?php

declare(strict_types=1);

namespace PaymentEngine\Kajabi\Support;

final class CallbackUrl
{
    public static function forPlugin(): string
    {
        return rtrim(
                (string) getenv('APP_URL'),
                '/'
            ) . '/plugin/callback.php';
    }
}