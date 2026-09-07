<?php

declare(strict_types=1);

namespace PaymentEngine\Kajabi\Tests;

use PHPUnit\Framework\TestCase;
use PaymentEngine\Kajabi\Support\ChargePayloadBuilder;

final class ChargePayloadBuilderTest extends TestCase
{
    public function testPayloadGeneration(): void
    {
        $payload =
            ChargePayloadBuilder::fromRequest(
                [
                    'firstName' => 'Samuel',
                    'lastName' => 'Andrew',
                    'email' => 'samuel@test.com',
                    'amount' => '15000',
                    'offerTitle' => 'Java Masterclass'
                ],
                'KAJABI-123',
                'https://merchant.test/callback'
            );

        $this->assertSame(
            'samuel@test.com',
            $payload['email']
        );

        $this->assertSame(
            'KAJABI-123',
            $payload['paymentIdentifier']
        );
    }
}