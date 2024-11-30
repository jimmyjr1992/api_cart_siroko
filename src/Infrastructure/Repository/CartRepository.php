<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\Cart;
use App\Domain\Repository\CartRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cart>
 */
class CartRepository extends ServiceEntityRepository implements CartRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cart::class);
    }

    public function findById(string $id): ?Cart
    {
        die('findById');
        // TODO: Implement findById() method.
    }

    public function save(Cart $cart): void
    {
        die('save');
        // TODO: Implement save() method.
    }

    public function delete(Cart $cart): void
    {
        die('delete');
        // TODO: Implement delete() method.
    }
}
