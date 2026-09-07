<?php

declare(strict_types=1);

namespace PaymentEngine\Kajabi\Support;

final class WebhookUrl
{
    public static function forPlugin(): string
    {
        return rtrim(
                (string) getenv('APP_URL'),
                '/'
            ) . '/plugin/webhook.php';
    }
}