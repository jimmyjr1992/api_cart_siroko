<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\CartItem;
use App\Domain\Repository\CartItemRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CartItem>
 */
class CartItemRepository extends ServiceEntityRepository implements CartItemRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CartItem::class);
    }

    public function findById(int $id): ?CartItem
    {
        die('findById');
        // TODO: Implement find() method.
    }

    public function save(CartItem $cartItem): void
    {
        die('save');
        // TODO: Implement save() method.
    }

    public function delete(CartItem $cartItem): void
    {
        die('delete');
        // TODO: Implement delete() method.
    }

}
