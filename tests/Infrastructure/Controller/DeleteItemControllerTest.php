<?php

namespace App\Tests\Application\Service;

use App\Application\Service\CartItemService;
use App\Application\Service\CartService;
use App\Infrastructure\Controller\DeleteItemController;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class DeleteItemControllerTest extends TestCase
{
    private DeleteItemController $controller;
    private CartService $cartService;
    private CartItemService $cartItemService;

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->cartService = $this->createMock(CartService::class);
        $this->cartItemService = $this->createMock(CartItemService::class);
        $this->controller = new DeleteItemController($this->cartService, $this->cartItemService);
    }

    /**
     * @throws Exception
     */
    public function testInvokeSuccessfullyDeletesItem()
    {
        $request = Request::create(
            '/api/delete_item',
            'DELETE',
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

        $this->cartItemService->expects($this->once())
            ->method('deleteCartItemFromData')
            ->with(['product_id' => 1]);

        $this->cartService->expects($this->once())
            ->method('getResponseCart')
            ->willReturn(json_encode(['success' => true]));

        $response = $this->controller->__invoke($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(json_encode(['success' => true]), $response->getContent());
    }

    public function testInvokeThrowsExceptionOnDeleteFailure()
    {
        $request = Request::create(
            '/api/delete_item',
            'DELETE',
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

        $this->cartItemService->expects($this->once())
            ->method('deleteCartItemFromData')
            ->with(['product_id' => 1])
            ->willThrowException(new Exception('Delete failed'));

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Delete failed');

        $this->controller->__invoke($request);
    }
}
