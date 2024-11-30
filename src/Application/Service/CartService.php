<?php

namespace App\Application\Service;

use App\Domain\Model\CartItem;
use App\Domain\Repository\CartRepositoryInterface;
use App\Domain\Service\CartServiceInterface;
use App\Domain\ValueObject\CartId;

class CartService implements CartServiceInterface
{
    private CartRepositoryInterface $cartRepository;

    public function __construct(CartRepositoryInterface $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function addItemToCart(CartId $cartId, CartItem $cartItem): void
    {
        die('addItemToCart');
        // TODO: Implement addItemToCart() method.
    }

    public function updateItemInCart(CartId $cartId, CartItem $cartItem): void
    {
        die('updateItemInCart');
        // TODO: Implement updateItemInCart() method.
    }

    public function removeItemFromCart(CartId $cartId, CartItem $cartItem): void
    {
        die('removeItemFromCart');
        // TODO: Implement removeItemFromCart() method.
    }

    public function getTotalItemCount(CartId $cartId): int
    {
        die('getTotalItemCount');
        // TODO: Implement getTotalItemCount() method.
    }

    public function checkoutCart(CartId $cartId): void
    {
        die('checkoutCart');
        // TODO: Implement checkoutCart() method.
    }
}