<?php

namespace App\Infrastructure\Controller;

use App\Application\Service\CartItemService;
use App\Application\Service\CartService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CheckoutController extends AbstractController
{
    private CartService $cartService;
    private CartItemService $cartItemService;

    /**
     * @param CartService $cartService
     * @param CartItemService $cartItemService
     */
    public function __construct(CartService $cartService, CartItemService $cartItemService)
    {
        $this->cartService = $cartService;
        $this->cartItemService = $cartItemService;
    }

    /**
     * Funcion que se ejecuta de forma predeterminada
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function __invoke(Request $request): JsonResponse
    {
        $this->cartService->getSessionCart($request->getSession());

        try {
            $this->cartService->checkoutCart($this->cartService->getCurrentCartId());
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }

        $request->getSession()->clear();

        return new JsonResponse($this->cartService->getResponsePayment(), 200, ['Content-Type' => 'application/json'], JSON_PRETTY_PRINT);
    }
}