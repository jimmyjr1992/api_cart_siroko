<?php

namespace App\Domain\Service;

use App\Domain\ValueObject\CartId;
use App\Domain\Model\CartItem;

interface CartServiceInterface
{
    /**
     * @param CartId $cartId
     * @param CartItem $cartItem
     * @return void
     */
    public function addItemToCart(CartId $cartId, CartItem $cartItem): void;

    /**
     * @param CartId $cartId
     * @param CartItem $cartItem
     * @return void
     */
    public function updateItemInCart(CartId $cartId, CartItem $cartItem): void;

    /**
     * @param CartId $cartId
     * @param CartItem $cartItem
     * @return void
     */
    public function removeItemFromCart(CartId $cartId, CartItem $cartItem): void;

    /**
     * @param CartId $cartId
     * @return int
     */
    public function getTotalItemCount(CartId $cartId): int;

    /**
     * @param CartId $cartId
     * @return void
     */
    public function checkoutCart(CartId $cartId): void;
}