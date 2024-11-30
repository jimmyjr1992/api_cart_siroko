<?php

namespace App\Domain\Repository;

use App\Domain\Model\Cart;

interface CartRepositoryInterface
{
    /**
     * @param string $id
     * @return Cart|null
     */
    public function findById(string $id): ?Cart;

    /**
     * @param Cart $cart
     * @return void
     */
    public function save(Cart $cart): void;

    /**
     * @param Cart $cart
     * @return void
     */
    public function delete(Cart $cart): void;
}