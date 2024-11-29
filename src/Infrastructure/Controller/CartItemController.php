<?php

namespace App\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartItemController extends AbstractController
{
    //#[Route('/api/cart-item-test', name: 'cart-item-test')]
    public function index(): Response
    {
        die('CartItemController');
        return $this->render('infrastructure/controller/cart_item/index.html.twig', [
            'controller_name' => 'CartItemController',
        ]);
    }
}
