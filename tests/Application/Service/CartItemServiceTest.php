<?php

namespace App\Tests\Application\Service;

use App\Application\Service\CartItemService;
use App\Domain\Model\CartItem;
use App\Domain\Repository\CartItemRepositoryInterface;
use App\Domain\Service\CartItemServiceInterface;
use App\Domain\Validator\CartItemValidator;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CartItemServiceTest extends TestCase
{
    private CartItemServiceInterface $cartItemService;
    private MockObject|CartItemRepositoryInterface $cartItemRepository;
    private MockObject|CartItemValidator $cartItemValidator;

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->cartItemRepository = $this->createMock(CartItemRepositoryInterface::class);
        $this->cartItemValidator = $this->createMock(CartItemValidator::class);
        $this->cartItemService = new CartItemService($this->cartItemRepository, $this->cartItemValidator);
    }

    /**
     * @throws Exception
     */
    public function testCreateCartItemFromData(): void
    {
        $data = [
            'product_id' => 1,
            'quantity' => 2,
            'price' => 10.00,
        ];

        $cartItem = new CartItem();
        $cartItem->setProductId(1);
        $cartItem->setQuantity(2);
        $cartItem->setPrice(10.00);
        $cartItem->setTotal(20.00);

        $this->cartItemValidator->expects($this->once())
            ->method('validateCreateData')
            ->with($data)
            ->willReturn(true);

        $this->cartItemRepository->expects($this->once())
            ->method('save')
            ->with($cartItem)
            ->willReturn($cartItem);

        $result = $this->cartItemService->createCartItemFromData($data);

        $this->assertEquals($cartItem, $result);
    }

    public function testCreateCartItemFromDataInvalidData(): void
    {
        $data = [
            'product_id' => 1,
            'quantity' => 2,
            'price' => 'doce',
        ];

        $this->cartItemValidator->expects($this->once())
            ->method('validateCreateData')
            ->with($data)
            ->willReturn(false);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Invalid data");

        $this->cartItemService->createCartItemFromData($data);
    }

    public function testUpdateCartItemFromData(): void
    {
        $data = [
            'cart_item_id' => 1,
            'quantity' => 3,
        ];
        $cartItem = new CartItem();
        $cartItem->setProductId(1);
        $cartItem->setQuantity(3);
        $cartItem->setPrice(10.00);
        $cartItem->setTotal(30.00);

        $this->cartItemValidator->expects($this->once())
            ->method('validateUpdateData')
            ->with($data)
            ->willReturn(true);

        $this->cartItemRepository->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($cartItem);

        $this->cartItemRepository->expects($this->once())
            ->method('save')
            ->with($cartItem)
            ->willReturn($cartItem);

        $result = $this->cartItemService->updateCartItemFromData($data);

        $this->assertEquals($cartItem, $result);
    }

    public function testUpdateCartItemFromDataInvalidData(): void
    {
        $data = [
            'cart_item_id' => 1,
            'quantity' => 'doce',
        ];

        $this->cartItemValidator->expects($this->once())
            ->method('validateUpdateData')
            ->with($data)
            ->willReturn(false);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Invalid data");

        $this->cartItemService->updateCartItemFromData($data);
    }

    public function testDeleteCartItemFromData(): void
    {
        $data = [
            'cart_item_id' => 1,
        ];
        $cartItem = new CartItem();

        $this->cartItemValidator->expects($this->once())
            ->method('validateDeleteData')
            ->with($data)
            ->willReturn(true);

        $this->cartItemRepository->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($cartItem);

        $this->cartItemRepository->expects($this->once())
            ->method('delete')
            ->with($cartItem);

        $this->cartItemService->deleteCartItemFromData($data);
    }

    public function testDeleteCartItemFromDataInvalidData(): void
    {
        $data = [
            'cart_item_id' => 'uno',
        ];

        $this->cartItemValidator->expects($this->once())
            ->method('validateDeleteData')
            ->with($data)
            ->willReturn(false);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Invalid data");

        $this->cartItemService->deleteCartItemFromData($data);
    }
}