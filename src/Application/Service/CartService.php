<?php

namespace App\Application\Service;

use App\Domain\Model\Cart;
use App\Domain\Model\CartItem;
use App\Domain\Repository\CartRepositoryInterface;
use App\Domain\Service\CartServiceInterface;
use App\Domain\ValueObject\CartId;
use Exception;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Domain\Model\Cart\CartStatus;

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
        $cart->setStatus(CartStatus::OPEN);

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

    /**
     * Obtiene el numero total de items que tiene el carrito
     *
     * @param CartId $cartId
     * @return int
     */
    public function getTotalItemCount(CartId $cartId): int
    {
        $cart = $this->cartRepository->findById($this->currentCartId->__toString());
        $cartItems = $cart->getCartItems()->getValues();

        $totalItems = 0;

        foreach ($cartItems as $cartItem) {
            $totalItems += $cartItem->getQuantity();
        }

        return $totalItems;
    }

    /**
     * @param CartId $cartId
     * @return void
     * @throws Exception
     */
    public function checkoutCart(CartId $cartId): void
    {
        $cart = $this->cartRepository->findById($this->currentCartId->__toString());
        $cartItems = $cart->getCartItems()->getValues();

        $totalPayment = 0;

        foreach ($cartItems as $cartItem) {
            $totalPayment += $cartItem->getTotal();
        }

        // Aqui se mandaria a una pasarela de pago, supongamos que es OK el pago
        $paymentSuccessful = true;

        //En caso negativo se gestionaria en nuestro caso simpre va a ser autorizada
        /*if (!$paymentSuccessful) {
            throw new Exception("Something wrong with the payment.");
        }*/

        $cart->setStatus(CartStatus::PAID);
        $this->cartRepository->save($cart);
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

    /**
     * @return string
     */
    public function getResponsePayment(): string
    {
        $cart = $this->cartRepository->findById($this->currentCartId->__toString());
        $cartItems = $cart->getCartItems()->getValues();

        return json_encode($cartItems);
    }

    /**
     * Vacia el carrito - ejemplo de como se podrian añadir nuevas funciones de manera sencilla
     *
     * @return void
     */
    public function clearCart(): void
    {
        $cart = $this->cartRepository->findById($this->currentCartId->__toString());
        $cart->getCartItems()->clear();
        $this->cartRepository->save($cart);
    }
}