<?php

namespace App\Tests\Domain\Model;

use App\Domain\Model\Cart;
use App\Domain\Model\CartItem;
use PHPUnit\Framework\TestCase;

class CartItemTest extends TestCase
{
    public function testCreateCartItem()
    {
       $cartItem = new CartItem();

       $this->assertInstanceOf(CartItem::class, $cartItem);
       $this->assertNull($cartItem->getId());
       $this->assertNull($cartItem->getProductId());
       $this->assertNull($cartItem->getQuantity());
       $this->assertNull($cartItem->getPrice());
       $this->assertNull($cartItem->getTotal());
    }

    public function testSetProductIdWithValidInteger(): void
    {
        $cartItem = new CartItem();
        $productId = 123;
        $cartItem->setProductId($productId);
        $this->assertEquals($productId, $cartItem->getProductId());
    }

    public function testSetProductIdWithValidString(): void
    {
        $cartItem = new CartItem();
        $productId = '123';
        $cartItem->setProductId($productId);
        $this->assertEquals($productId, $cartItem->getProductId());
    }

    public function testSetProductIdWithInvalidString(): void
    {
        $cartItem = new CartItem();
        $productId = 'abc';
        $this->expectException(\TypeError::class);
        $cartItem->setProductId($productId);
    }

    public function testSetProductIdWithInvalidStringWithNumbers(): void
    {
        $cartItem = new CartItem();
        $productId = '123abc';
        $this->expectException(\TypeError::class);
        $cartItem->setProductId($productId);
    }

    public function testSetProductIdWithFloat(): void
    {
        $cartItem = new CartItem();
        $productId = 12.3;
        $cartItem->setProductId($productId);
        $this->assertEquals(intval($productId), $cartItem->getProductId());
    }

    public function testSetProductIdWithNegativeInteger(): void
    {
        $cartItem = new CartItem();
        $productId = -123;
        $this->expectException(\TypeError::class);
        $cartItem->setProductId($productId);
    }

    public function testSetQuantityWithValidInteger(): void
    {
        $cartItem = new CartItem();
        $quantity = 5;
        $cartItem->setQuantity($quantity);
        $this->assertEquals($quantity, $cartItem->getQuantity());
    }

    public function testSetQuantityWithValidString(): void
    {
        $cartItem = new CartItem();
        $quantity = '5';
        $cartItem->setQuantity($quantity);
        $this->assertEquals($quantity, $cartItem->getQuantity());
    }

    public function testSetQuantityWithInvalidString(): void
    {
        $cartItem = new CartItem();
        $quantity = 'cinco';
        $this->expectException(\TypeError::class);
        $cartItem->setQuantity($quantity);
    }

    public function testSetQuantityWithInvalidStringWithNumbers(): void
    {
        $cartItem = new CartItem();
        $quantity = '5cinco';
        $this->expectException(\TypeError::class);
        $cartItem->setQuantity($quantity);
    }

    public function testSetQuantityWithFloat(): void
    {
        $cartItem = new CartItem();
        $quantity = 5.5;
        $cartItem->setQuantity($quantity);
        $this->assertEquals(intval($quantity), $cartItem->getQuantity());
    }

    public function testSetQuantityWithNegativeInteger(): void
    {
        $cartItem = new CartItem();
        $quantity = -5;
        $this->expectException(\TypeError::class);
        $cartItem->setQuantity($quantity);
    }

    public function testSetPriceWithValidFloat(): void
    {
        $cartItem = new CartItem();
        $price = 10.99;
        $cartItem->setPrice($price);
        $this->assertEquals($price, $cartItem->getPrice());
    }

    public function testSetPriceWithValidString(): void
    {
        $cartItem = new CartItem();
        $price = '10.99';
        $cartItem->setPrice($price);
        $this->assertEquals($price, $cartItem->getPrice());
    }

    public function testSetPriceWithInvalidString(): void
    {
        $cartItem = new CartItem();
        $price = 'abc';
        $this->expectException(\TypeError::class);
        $cartItem->setPrice($price);
    }

    public function testSetPriceWithValidInteger(): void
    {
        $cartItem = new CartItem();
        $price = 10;
        $cartItem->setPrice($price);
        $this->assertEquals($price, $cartItem->getPrice());
    }

    public function testSetPriceWithNegativeFloat(): void
    {
        $cartItem = new CartItem();
        $price = -10.99;
        $this->expectException(\TypeError::class);
        $cartItem->setPrice($price);
    }

    public function testSetPriceWithEmptyString(): void
    {
        $cartItem = new CartItem();
        $price = '';
        $this->expectException(\TypeError::class);
        $cartItem->setPrice($price);
    }

    public function testSetTotalWithValidFloat(): void
    {
        $cartItem = new CartItem();
        $total = 54.95;
        $cartItem->setTotal($total);
        $this->assertEquals($total, $cartItem->getTotal());
    }

    public function testSetTotalWithValidString(): void
    {
        $cartItem = new CartItem();
        $total = '54.95';
        $cartItem->setTotal($total);
        $this->assertEquals($total, $cartItem->getTotal());
    }

    public function testSetTotalWithInvalidString(): void
    {
        $cartItem = new CartItem();
        $total = '54.95a';
        $this->expectException(\TypeError::class);
        $cartItem->setTotal($total);
    }

    public function testSetTotalWithValidInteger(): void
    {
        $cartItem = new CartItem();
        $total = 54;
        $cartItem->setTotal($total);
        $this->assertEquals($total, $cartItem->getTotal());
    }

    public function testSetTotalWithNegativeFloat(): void
    {
        $cartItem = new CartItem();
        $total = -54.95;
        $this->expectException(\TypeError::class);
        $cartItem->setTotal($total);
    }

    public function testSetTotalWithEmptyString(): void
    {
        $cartItem = new CartItem();
        $total = '';
        $this->expectException(\TypeError::class);
        $cartItem->setTotal($total);
    }

    public function testAddCart(): void
    {
        $cartItem = new CartItem();
        $cart = new Cart(); // Crea un objeto Cart para la prueba

        $cartItem->addCart($cart);

        $this->assertTrue($cartItem->getCarts()->contains($cart));
        $this->assertTrue($cart->getCartItems()->contains($cartItem));
    }

    public function testRemoveCart(): void
    {
        $cartItem = new CartItem();
        $cart = new Cart(); // Crea un objeto Cart para la prueba

        $cartItem->addCart($cart); // Agrega el cart al cartItem
        $cartItem->removeCart($cart); // Elimina el cart del cartItem

        $this->assertFalse($cartItem->getCarts()->contains($cart));
        $this->assertFalse($cart->getCartItems()->contains($cartItem));
    }

    public function testJsonSerialize(): void
    {
        $cartItem = new CartItem();
        $cartItem->setProductId(123);
        $cartItem->setQuantity(5);
        $cartItem->setPrice(10.99);
        $cartItem->setTotal(54.95);

        $expectedJson = [
            'id' => null,
            'productId' => 123,
            'quantity' => 5,
            'price' => 10.99,
            'total' => 54.95,
        ];

        $this->assertEquals($expectedJson, $cartItem->jsonSerialize());
    }
}
