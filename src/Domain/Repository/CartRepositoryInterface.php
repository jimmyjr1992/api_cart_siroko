<?php

namespace App\Domain\Repository;

use App\Domain\Model\Cart;
use App\Domain\Model\CartItem;
use App\Domain\ValueObject\CartId;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

interface CartRepositoryInterface
{
    /**
     * @param string $id
     * @return Cart|null
     */
    public function findById(string $id): ?Cart;

    /**
     * @param Cart $cart
     * @return Cart
     */
    public function save(Cart $cart): Cart;

    /**
     * @param Cart $cart
     * @return void
     */
    public function delete(Cart $cart): void;
}