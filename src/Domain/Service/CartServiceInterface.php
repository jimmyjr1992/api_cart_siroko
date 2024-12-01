<?php

namespace App\Domain\Service;

use App\Domain\ValueObject\CartId;
use App\Domain\Model\CartItem;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

interface CartServiceInterface
{
    /**
     * @param SessionInterface $session
     * @return void
     */
    public function getSessionCart(SessionInterface $session): void;

    /**
     * @return CartId
     */
    public function getCurrentCartId(): CartId;

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

    /**
     * @return string
     */
    public function getResponseCart(): string;
}