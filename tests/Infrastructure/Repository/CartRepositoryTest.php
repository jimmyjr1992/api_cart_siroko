<?php

namespace App\Tests\Infrastructure\Repository;

use App\Domain\Model\Cart;
use App\Domain\Repository\CartRepositoryInterface;
use App\Infrastructure\Repository\CartRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Doctrine\ManagerRegistry;

class CartRepositoryTest extends TestCase
{
    private CartRepositoryInterface $cartRepository;
    private MockObject|EntityManagerInterface $entityManager;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        $classMetadataMock = $this->createMock(ClassMetadata::class);
        $classMetadataMock->method('getName')->willReturn(Cart::class);

        $this->entityManager->expects($this->any())
            ->method('getClassMetadata')
            ->with(Cart::class)
            ->willReturn($classMetadataMock);

        $managerRegistry = $this->createMock(ManagerRegistry::class);
        $managerRegistry->method('getManagerForClass')->willReturn($this->entityManager);

        $this->cartRepository = new CartRepository($managerRegistry, $this->entityManager);
    }

    public function testSave(): void
    {
        $cart = new Cart();

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($cart);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $result = $this->cartRepository->save($cart);
        $this->assertSame($cart, $result);
    }

    public function testDelete(): void
    {
        $cart = new Cart();

        $this->entityManager->expects($this->once())
            ->method('remove')
            ->with($cart);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->cartRepository->delete($cart);
    }
}
