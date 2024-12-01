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
        if (!$this->validateCreateData($data)) {
            throw new Exception("Invalid data");
        }

        $cartItem = new CartItem();

        $cartItem->setProductId($data['product_id']);
        $cartItem->setQuantity($data['quantity']);
        $cartItem->setPrice($data['price']);
        $this->setTotalPrice($cartItem);

        $this->cartItemRepository->save($cartItem);

        return $cartItem;
    }

    /**
     * Actualiza un CartItem a partir de un array
     *
     * @param array $data
     * @return CartItem
     * @throws Exception
     */
    public function updateCartItemFromData(array $data): CartItem
    {
        if (!$this->validateUpdateData($data)) {
            throw new Exception("Invalid data");
        }

        $cartItem = $this->cartItemRepository->findById($data['cart_item_id']);

        $cartItem->setQuantity($data['quantity']);
        $this->setTotalPrice($cartItem);

        $this->cartItemRepository->save($cartItem);

        return $cartItem;
    }

    /**
     * Elimina un CartItem del carrito
     *
     * @param array $data
     * @return void
     * @throws Exception
     */
    public function deleteCartItemFromData(array $data): void
    {
        if (!$this->validateDeleteData($data)) {
            throw new Exception("Invalid data");
        }

        $cartItem = $this->cartItemRepository->findById($data['cart_item_id']);

        $this->cartItemRepository->delete($cartItem);
    }

    /**
     * Valida que el array contiene los datos necesarios para construir el CartItem
     *
     * @param array $data
     * @return bool
     */
    private function validateCreateData(array $data): bool
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
     * Valida que el array contiene los datos necesarios para actualizar el CartItem
     *
     * @param array $data
     * @return bool
     */
    private function validateUpdateData(array $data): bool
    {
        if (!isset($data['cart_item_id']) || !is_int($data['cart_item_id'])) {
            return false;
        }

        if (!isset($data['quantity']) || !is_int($data['quantity'])) {
            return false;
        }

        return true;
    }

    /**
     * Valida que el array contiene los datos necesarios para eliminar el CartItem
     *
     * @param array $data
     * @return bool
     */
    private function validateDeleteData(array $data): bool
    {
        if (!isset($data['cart_item_id']) || !is_int($data['cart_item_id'])) {
            return false;
        }

        return true;
    }

    /**
     * Obtiene el precio total, a partir del precio y la cantidad del articulo
     *
     * @param CartItem $cartItem
     * @return void
     */
    private function setTotalPrice(CartItem $cartItem): void
    {
        $totalPrice = $cartItem->getPrice() * $cartItem->getQuantity();

        $cartItem->setTotal($totalPrice);
    }
}