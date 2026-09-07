<?php

declare(strict_types=1);

namespace Remita\Kajabi\Tests;

use PHPUnit\Framework\TestCase;

class TransactionVerifierTest extends TestCase
{
    public function testSuccessfulVerificationResponse(): void
    {
        $response = [
            'status' => 'SUCCESS',
            'reference' => 'KAJABI-12345'
        ];

        $this->assertEquals(
            'SUCCESS',
            $response['status']
        );
    }

    public function testFailedVerificationResponse(): void
    {
        $response = [
            'status' => 'FAILED',
            'reference' => 'KAJABI-12345'
        ];

        $this->assertEquals(
            'FAILED',
            $response['status']
        );
    }
}