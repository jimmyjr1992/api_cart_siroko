<?php

namespace App\Application\Service;

use App\Domain\Model\CartItem;
use App\Domain\Repository\CartItemRepositoryInterface;
use App\Domain\Service\CartItemServiceInterface;
use Exception;

class CartItemService implements CartItemServiceInterface
{
    private CartItemRepositoryInterface $cartItemRepository;

    /**
     * @param CartItemRepositoryInterface $cartItemRepository
     */
    public function __construct(CartItemRepositoryInterface $cartItemRepository)
    {
        $this->cartItemRepository = $cartItemRepository;
    }

    /**
     * Crea un CartItem a partir de un array
     *
     * @param array $data
     * @return CartItem
     * @throws Exception
     */
    public function createCartItemFromData(array $data): CartItem
    {
        if (!$this->validateData($data)) {
            throw new Exception("Invalid data");
        }

        $cartItem = new CartItem();
        $totalPrice = $this->getTotalPrice($data);

        $cartItem->setProductId($data['product_id']);
        $cartItem->setQuantity($data['quantity']);
        $cartItem->setPrice($data['price']);
        $cartItem->setTotal($totalPrice);

        $this->cartItemRepository->save($cartItem);

        return $cartItem;
    }

    /**
     * Valida que el array contiene los datos necesarios para construir el CartItem
     *
     * @param array $data
     * @return bool
     */
    private function validateData(array $data): bool
    {
        if (!isset($data['product_id']) || !is_int($data['product_id'])) {
            return false;
        }

        if (!isset($data['quantity']) || !is_int($data['quantity'])) {
            return false;
        }

        if (!isset($data['price']) || !is_float($data['price'])) {
            return false;
        }

        return true;
    }

    /**
     * Obtiene el precio total, a partir del precio y la cantidad del articulo
     *
     * @param array $data
     * @return float
     */
    private function getTotalPrice(array $data): float
    {
        $totalPrice = $data['price'] * $data['quantity'];
        return round($totalPrice);
    }
}