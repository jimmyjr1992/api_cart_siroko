<?php

namespace App\Tests\Domain\Model;

use App\Domain\Model\Cart;
use App\Domain\Model\Cart\CartStatus;
use App\Domain\Model\CartItem;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class CartTest extends TestCase
{
    public function testCartCreation(): void
    {
        $cart = new Cart();

        $this->assertInstanceOf(Cart::class, $cart);
        $this->assertNull($cart->getId());
        $this->assertNull($cart->getUserId());
        $this->assertInstanceOf(ArrayCollection::class, $cart->getCartItems());
        $this->assertNull($cart->getStatus());
    }

    public function testSetUserIdWithValidInteger(): void
    {
        $cart = new Cart();
        $userId = 123;
        $cart->setUserId($userId);
        $this->assertEquals($userId, $cart->getUserId());
    }

    public function testSetUserIdWithStringInteger(): void
    {
        $cart = new Cart();
        $userId = '123';
        $cart->setUserId($userId);
        $this->assertEquals($userId, $cart->getUserId());
    }

    public function testSetUserIdWithInvalidString(): void
    {
        $cart = new Cart();
        $userId = 'abc';
        $this->expectException(\TypeError::class);
        $cart->setUserId($userId);
    }

    public function testSetUserIdWithFloat(): void
    {
        $cart = new Cart();
        $userId = 12.5;
        $cart->setUserId($userId);
        $this->assertEquals(intval($userId), $cart->getUserId());
    }

    public function testSetUserIdWithNegativeInteger(): void
    {
        $cart = new Cart();
        $userId = -123;
        $this->expectException(\TypeError::class);
        $cart->setUserId($userId);
    }

    public function testSetUserIdWithNull(): void
    {
        $cart = new Cart();
        $userId = null;
        $cart->setUserId($userId);
        $this->assertNull($cart->getUserId());
    }

    public function testAddCartItem(): void
    {
        $cart = new Cart();
        $cartItem = new CartItem();

        $cart->addCartItem($cartItem);

        $this->assertTrue($cart->getCartItems()->contains($cartItem));
    }

    public function testRemoveCartItem(): void
    {
        $cart = new Cart();
        $cartItem = new CartItem();
        $cart->addCartItem($cartItem);

        $cart->removeCartItem($cartItem);

        $this->assertFalse($cart->getCartItems()->contains($cartItem));
    }

    public function testSetStatus(): void
    {
        $cart = new Cart();

        $cart->setStatus(CartStatus::OPEN);
        $this->assertEquals(CartStatus::OPEN, $cart->getStatus());

        $cart->setStatus(CartStatus::PAID);
        $this->assertEquals(CartStatus::PAID, $cart->getStatus());
    }
}
