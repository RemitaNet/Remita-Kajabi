<?php

declare(strict_types=1);

namespace Remita\Kajabi\Tests;

use PHPUnit\Framework\TestCase;
use Remita\Kajabi\Support\PaymentStatusMapper;

class PaymentStatusMapperTest extends TestCase
{
    public function testMapsSuccess(): void
    {
        $this->assertEquals(
            'paid',
            PaymentStatusMapper::map(
                'SUCCESS'
            )
        );
    }

    public function testMapsPending(): void
    {
        $this->assertEquals(
            'pending',
            PaymentStatusMapper::map(
                'PENDING'
            )
        );
    }

    public function testMapsFailed(): void
    {
        $this->assertEquals(
            'failed',
            PaymentStatusMapper::map(
                'FAILED'
            )
        );
    }
}