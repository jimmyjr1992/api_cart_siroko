<?php

namespace App\Tests\Infrastructure\Controller;

use App\Application\Service\CartItemService;
use App\Application\Service\CartService;
use App\Domain\ValueObject\CartId;
use App\Infrastructure\Controller\CheckoutController;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class CheckoutControllerTest extends TestCase
{
    private CheckoutController $controller;
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
        $this->controller = new CheckoutController($this->cartService, $this->cartItemService);
    }

    /**
     * @throws Exception
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testInvokeSuccessfullyCheckoutsCart()
    {
        $request = Request::create(
            '/api/checkout',
            'POST',
            [],
            [],
            [],
            [],
            json_encode(['test' => true])
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
            ->method('checkoutCart')
            ->with($cartIdMock);

        $this->cartService->expects($this->once())
            ->method('getResponsePayment')
            ->willReturn(json_encode(['success' => true]));

        $response = $this->controller->__invoke($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(json_encode(['success' => true]), $response->getContent());
        $this->assertTrue($request->getSession()->isStarted());
        $this->assertTrue($request->getSession()->isEmpty());
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testInvokeThrowsExceptionOnCheckoutFailure()
    {
        $request = Request::create(
            '/api/checkout',
            'POST',
            [],
            [],
            [],
            [],
            json_encode(['test' => true])
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
            ->method('checkoutCart')
            ->with($cartIdMock)
            ->willThrowException(new Exception('Checkout failed'));

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Checkout failed');

        $this->controller->__invoke($request);
    }
}
