<?php

namespace App\Tests\Domain\Model\Cart;

use App\Domain\Model\Cart\CartStatus;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class CartStatusTest extends TestCase
{
    public function testOpenConstant(): void
    {
        $this->assertSame(0, CartStatus::OPEN);
    }

    public function testPaidConstant(): void
    {
        $this->assertSame(1, CartStatus::PAID);
    }

    public function testInvalidConstant(): void
    {
        $reflectionClass = new ReflectionClass(CartStatus::class);
        $definedConstants = $reflectionClass->getConstants();

        $this->assertFalse(isset($definedConstants['INVALID_CONSTANT']));
    }
}
