<?php

namespace App\Tests\Infrastructure\Repository;

use App\Domain\Model\CartItem;
use App\Domain\Repository\CartItemRepositoryInterface;
use App\Infrastructure\Repository\CartItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CartItemRepositoryTest extends TestCase
{
    private CartItemRepositoryInterface $cartItemRepository;
    private MockObject|EntityManagerInterface $entityManager;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $managerRegistry = $this->createMock(ManagerRegistry::class);
        $managerRegistry->method('getManagerForClass')->willReturn($this->entityManager);

        $this->cartItemRepository = new CartItemRepository($managerRegistry, $this->entityManager);
    }

    public function testSave(): void
    {
        $cartItem = new CartItem();

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($cartItem);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $result = $this->cartItemRepository->save($cartItem);
        $this->assertSame($cartItem, $result);
    }

    public function testDelete(): void
    {
        $cartItem = new CartItem();

        $this->entityManager->expects($this->once())
            ->method('remove')
            ->with($cartItem);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->cartItemRepository->delete($cartItem);
    }
}
