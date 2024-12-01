<?php

namespace App\Tests\Infrastructure\Controller;

use App\Application\Service\CartItemService;
use App\Application\Service\CartService;
use App\Domain\ValueObject\CartId;
use App\Infrastructure\Controller\GetTotalItemsController;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class GetTotalItemsControllerTest extends TestCase
{
    private GetTotalItemsController $controller;
    private CartService $cartService;
    private CartItemService $cartItemService;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->cartService = $this->createMock(CartService::class);
        $this->cartItemService = $this->createMock(CartItemService::class);
        $this->controller = new GetTotalItemsController($this->cartService, $this->cartItemService);
    }

    public function testGetTotalItemsSuccessfully()
    {
        $request = Request::create(
            '/api/get_total_items',
            'GET',
            [],
            [],
            [],
            [],
            json_encode(['product_id' => 1])
        );
        $request->setSession(new Session());

        $this->cartService->expects($this->once())
            ->method('getSessionCart')
            ->with($request->getSession());

        $cartIdMock = new CartId(123);
        $this->cartService->expects($this->once())
            ->method('getCurrentCartId')
            ->willReturn($cartIdMock);

        $this->cartService->expects($this->once())
            ->method('getTotalItemCount')
            ->with($cartIdMock)
            ->willReturn(3);

        $response = $this->controller->__invoke($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(3, $response->getContent());
    }

    public function testGetTotalItemsFailure()
    {
        $request = Request::create(
            '/api/get_total_items',
            'GET',
            [],
            [],
            [],
            [],
            json_encode(['product_id' => 1])
        );
        $request->setSession(new Session());

        $this->cartService->expects($this->once())
            ->method('getSessionCart')
            ->with($request->getSession());

        $cartIdMock = new CartId(123);
        $this->cartService->expects($this->once())
            ->method('getCurrentCartId')
            ->willReturn($cartIdMock);

        $this->cartService->expects($this->once())
            ->method('getTotalItemCount')
            ->with($cartIdMock)
            ->willThrowException(new \Exception('Error retrieving total items'));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Error retrieving total items');

        $this->controller->__invoke($request);
    }
}
