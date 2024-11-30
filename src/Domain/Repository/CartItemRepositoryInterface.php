<?php

namespace App\Domain\Repository;

use App\Domain\Model\CartItem;

interface CartItemRepositoryInterface
{
    /**
     * @param int $id
     * @return CartItem|null
     */
    public function findById(int $id): ?CartItem;

    /**
     * @param CartItem $cartItem
     * @return void
     */
    public function save(CartItem $cartItem): void;

    /**
     * @param CartItem $cartItem
     * @return void
     */
    public function delete(CartItem $cartItem): void;
}