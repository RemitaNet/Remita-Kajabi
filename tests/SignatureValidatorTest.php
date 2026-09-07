<?php

declare(strict_types=1);

namespace Remita\Kajabi\Tests;

use PHPUnit\Framework\TestCase;
use Remita\Kajabi\Support\SignatureValidator;

class SignatureValidatorTest extends TestCase
{
    public function testValidSignature(): void
    {
        putenv('REMITA_SECRET_KEY=test-secret');

        $payload = '{"event":"payment.success"}';

        $signature = hash_hmac(
            'sha512',
            $payload,
            'test-secret'
        );

        $validator =
            new SignatureValidator();

        $this->assertTrue(
            $validator->validate(
                $payload,
                $signature
            )
        );
    }

    public function testInvalidSignature(): void
    {
        putenv('REMITA_SECRET_KEY=test-secret');

        $validator =
            new SignatureValidator();

        $this->assertFalse(
            $validator->validate(
                '{"event":"payment.success"}',
                'invalid-signature'
            )
        );
    }
}