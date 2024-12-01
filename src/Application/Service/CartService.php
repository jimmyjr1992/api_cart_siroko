<?php

namespace App\Application\Service;

use App\Domain\Model\Cart;
use App\Domain\Model\CartItem;
use App\Domain\Repository\CartRepositoryInterface;
use App\Domain\Service\CartServiceInterface;
use App\Domain\ValueObject\CartId;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService implements CartServiceInterface
{
    private CartRepositoryInterface $cartRepository;
    private CartId $currentCartId;

    /**
     * @param CartRepositoryInterface $cartRepository
     */
    public function __construct(CartRepositoryInterface $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    /**
     * Obtiene el carrito actual del cliente, y si no crea uno nuevo
     *
     * @param SessionInterface $session
     * @return void
     */
    public function getSessionCart(SessionInterface $session): void
    {
        $currentCartId = $session->get('cartId');

        if (!$currentCartId) {
            $session->start();

            try {
                $cart = $this->newCart();
                $session->set('cartId', $cart->getId());
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }

        $this->setCurrentCart($session);
    }

    /**
     * Setea el CartId actual
     *
     * @param SessionInterface $session
     * @return void
     */
    private function setCurrentCart(SessionInterface $session): void
    {
        $cartId = $session->get('cartId');
        $this->currentCartId = new CartId($cartId);
    }

    /**
     * Crea un carrito nuevo vacio
     *
     * @return Cart
     * @throws Exception
     */
    private function newCart(): Cart
    {
        $cart = new Cart();
        $cart->setStatus(0);

        try {
            $this->cartRepository->save($cart);

            return $cart;
        } catch (Exception $e) {
            throw new Exception("Failed to create cart.".$e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Devuelve el CartId actual
     *
     * @return CartId
     */
    public function getCurrentCartId(): CartId
    {
        return $this->currentCartId;
    }

    /**
     * Añade un CartItem al carrito actual
     *
     * @param CartId $cartId
     * @param CartItem $cartItem
     * @return void
     */
    public function addItemToCart(CartId $cartId, CartItem $cartItem): void
    {
        $cart = $this->cartRepository->findById($cartId->__toString());
        $cart->addCartItem($cartItem);
        $this->cartRepository->save($cart);
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

    /**
     * Devuelve el Json para la respuesta del controlador
     *
     * @return string
     */
    public function getResponseCart(): string
    {
        $cart = $this->cartRepository->findById($this->currentCartId->__toString());
        $cartItems = $cart->getCartItems()->getValues();

        return json_encode($cartItems);
    }
}