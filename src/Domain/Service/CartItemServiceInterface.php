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
}