<?php

declare(strict_types=1);

namespace PaymentEngine\Kajabi\Tests;

use PHPUnit\Framework\TestCase;
use PaymentEngine\Kajabi\Support\PaymentIdentifier;

final class PaymentIdentifierTest extends TestCase
{
    public function testGenerateIdentifier(): void
    {
        $identifier =
            PaymentIdentifier::generate(
                'JAVA101'
            );

        $this->assertStringStartsWith(
            'KAJABI-JAVA101-',
            $identifier
        );
    }
}