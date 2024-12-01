<?php

namespace App\Infrastructure\Controller;

use App\Application\Service\CartItemService;
use App\Application\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetTotalItemsController extends AbstractController
{
    private CartService $cartService;
    private CartItemService $cartItemService;

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

        $totalItems = $this->cartService->getTotalItemCount($this->cartService->getCurrentCartId());

        return new JsonResponse($totalItems, 200, ['Content-Type' => 'application/json'], JSON_PRETTY_PRINT);
    }
}