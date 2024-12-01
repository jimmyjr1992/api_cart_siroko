<?php

namespace App\Application\Service;

use App\Domain\Model\CartItem;
use App\Domain\Repository\CartItemRepositoryInterface;
use App\Domain\Service\CartItemServiceInterface;
use App\Domain\Validator\CartItemValidator;
use Exception;

class CartItemService implements CartItemServiceInterface
{
    private CartItemRepositoryInterface $cartItemRepository;
    private CartItemValidator $cartItemValidator;

    /**
     * @param CartItemRepositoryInterface $cartItemRepository
     */
    public function __construct(CartItemRepositoryInterface $cartItemRepository, CartItemValidator $cartItemValidator)
    {
        $this->cartItemRepository = $cartItemRepository;
        $this->cartItemValidator = $cartItemValidator;
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
        if (!$this->cartItemValidator->validateCreateData($data)) {
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
        if (!$this->cartItemValidator->validateUpdateData($data)) {
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
        if (!$this->cartItemValidator->validateDeleteData($data)) {
            throw new Exception("Invalid data");
        }

        $cartItem = $this->cartItemRepository->findById($data['cart_item_id']);

        $this->cartItemRepository->delete($cartItem);
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