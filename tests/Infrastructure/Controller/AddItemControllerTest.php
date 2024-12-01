<?php

namespace App\Tests\Infrastructure\Controller;

use App\Application\Service\CartItemService;
use App\Application\Service\CartService;
use App\Domain\Model\CartItem;
use App\Domain\ValueObject\CartId;
use App\Infrastructure\Controller\AddItemController;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class AddItemControllerTest extends TestCase
{
    private CartService $cartService;
    private CartItemService $cartItemService;
    private AddItemController $controller;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->cartService = $this->createMock(CartService::class);
        $this->cartItemService = $this->createMock(CartItemService::class);
        $this->controller = new AddItemController($this->cartService, $this->cartItemService);
    }

    /**
     * @throws Exception
     * @throws \Exception
     */
    public function testInvokeSuccessfullyAddsItem()
    {
        $request = Request::create(
            '/api/add_item',
            'POST',
            ['product_id' => 1, 'quantity' => 2, 'price' => 10.0],
            [],
            [],
            [],
            json_encode(['test' => true])
        );
        $request->setSession(new Session());

        $cartItemMock = $this->createMock(CartItem::class);
        $this->cartItemService->expects($this->once())
            ->method('createCartItemFromData')
            ->willReturn($cartItemMock);

        $cartId = new CartId(123);
        $this->cartService->expects($this->once())
            ->method('getCurrentCartId')
            ->willReturn($cartId);

        $this->cartService->expects($this->once())
            ->method('addItemToCart')
            ->with($this->anything(), $cartItemMock);

        $this->cartService->expects($this->once())
            ->method('getResponseCart')
            ->willReturn(json_encode(['success' => true]));

        $response = $this->controller->__invoke($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(json_encode(['success' => true]), $response->getContent());
    }

    /**
     * @throws \Exception
     */
    public function testInvokeThrowsExceptionOnInvalidData()
    {
        $request = Request::create(
            '/api/add_item',
            'POST',
            [],
            [],
            [],
            [],
            json_encode(['test' => true])
        );
        $request->setSession(new Session());

        $this->cartItemService->expects($this->once())
            ->method('createCartItemFromData')
            ->will($this->throwException(new \Exception('Invalid data')));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid data');

        $this->controller->__invoke($request);
    }
}
