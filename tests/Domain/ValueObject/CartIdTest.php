<?php

namespace App\Tests\Domain\ValueObject;

use App\Domain\ValueObject\CartId;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CartIdTest extends TestCase
{
    public function testCreateValidCartId(): void
    {
        $id = 123;
        $cartId = new CartId($id);
        $this->assertEquals($id, $cartId->getValue());
    }

    public function testCreateCartIdWithString(): void
    {
        $id = '123';
        $cartId = new CartId($id);
        $this->assertEquals($id, $cartId->getValue());
    }

    public function testCreateCartIdWithNegativeInteger(): void
    {
        $id = -123;
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid Cart Id");
        new CartId($id);
    }

    public function testCreateCartIdWithEmptyString(): void
    {
        $this->expectException(\TypeError::class);
        new CartId('');
    }

    public function testCreateCartIdWithFloat(): void
    {
        $id = 12.3;
        $cartId = new CartId($id);
        $this->assertEquals(intval($id), $cartId->getValue());
    }

    public function testCreateCartIdWithZero(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid Cart Id");
        new CartId(0);
    }

    public function testToString(): void
    {
        $id = 123;
        $cartId = new CartId($id);
        $this->assertEquals(strval($id), $cartId->__toString());
    }
}
