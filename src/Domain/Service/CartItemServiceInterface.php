<?php

namespace App\Domain\Service;

use App\Domain\Model\CartItem;

interface CartItemServiceInterface
{
    /**
     * @param array $data
     * @return CartItem
     */
    public function createCartItemFromData(array $data): CartItem;

    /**
     * @param array $data
     * @return CartItem
     */
    public function updateCartItemFromData(array $data): CartItem;

    /**
     * @param array $data
     * @return void
     */
    public function deleteCartItemFromData(array $data): void;
}