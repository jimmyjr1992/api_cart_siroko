<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\CartItem;
use App\Domain\Repository\CartItemRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CartItem>
 */
class CartItemRepository extends ServiceEntityRepository implements CartItemRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    /**
     * @param ManagerRegistry $registry
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, CartItem::class);
        $this->entityManager = $entityManager;
    }

    /**
     * Recupera el CartItem de base de datos a partir de su ID
     *
     * @param int $id
     * @return CartItem|null
     */
    public function findById(int $id): ?CartItem
    {
        return $this->find($id);
    }

    /**
     * Guarda en base de datos un objeto CartItem
     *
     * @param CartItem $cartItem
     * @return CartItem
     */
    public function save(CartItem $cartItem): CartItem
    {
        $this->entityManager->persist($cartItem);
        $this->entityManager->flush();

        return $cartItem;
    }

    /**
     * Borra de base de datos un CartItem a partir del propio objeto CartItem
     *
     * @param CartItem $cartItem
     * @return void
     */
    public function delete(CartItem $cartItem): void
    {
        $this->entityManager->remove($cartItem);
        $this->entityManager->flush();
    }
}
