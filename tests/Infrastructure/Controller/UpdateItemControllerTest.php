<?php

namespace App\Tests\Infrastructure\Controller;

use App\Application\Service\CartItemService;
use App\Application\Service\CartService;
use App\Domain\Model\CartItem;
use App\Infrastructure\Controller\UpdateItemController;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class UpdateItemControllerTest extends TestCase
{
    private CartService $cartService;
    private CartItemService $cartItemService;
    private UpdateItemController $controller;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->cartService = $this->createMock(CartService::class);
        $this->cartItemService = $this->createMock(CartItemService::class);
        $this->controller = new UpdateItemController($this->cartService, $this->cartItemService);
    }

    /**
     * @throws \Exception
     */
    public function testUpdateItemSuccess(): void
    {
        $request = Request::create(
            '/api/update_item',
            'PUT',
            [],
            [],
            [],
            [],
            json_encode(['productId' => 1, 'quantity' => 2])
        );
        $request->setSession(new Session());

        // Expectations for mocked services
        $this->cartService
            ->expects($this->once())
            ->method('getSessionCart')
            ->with($request->getSession());

        $cartItemMock = new CartItem(123); // Assuming CartItem has a constructor
        $this->cartItemService
            ->expects($this->once())
            ->method('updateCartItemFromData')
            ->with(['productId' => 1, 'quantity' => 2])
            ->willReturn($cartItemMock);

        $this->cartService
            ->expects($this->once())
            ->method('getResponseCart')
            ->willReturn(json_encode(['productId' => 1, 'quantity' => 2]));

        // Execute the controller
        $response = $this->controller->__invoke($request);

        // Assertions
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['productId' => 1, 'quantity' => 2], json_decode($response->getContent(), true));
    }

    public function testUpdateItemFailure(): void
    {
        $request = Request::create(
            '/api/update_item',
            'PUT',
            [],
            [],
            [],
            [],
            json_encode(['product_id' => 1, 'quantity' => 2, 'price' => 10.0])
        );
        $request->setSession(new Session());

        $this->cartService
            ->expects($this->once())
            ->method('getSessionCart')
            ->with($request->getSession());

        $this->cartItemService
            ->expects($this->once())
            ->method('updateCartItemFromData')
            ->with(['product_id' => 1, 'quantity' => 2, 'price' => 10.0])
            ->willThrowException(new \Exception('Error updating cart item'));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Error updating cart item');
        $this->controller->__invoke($request);
    }
}
