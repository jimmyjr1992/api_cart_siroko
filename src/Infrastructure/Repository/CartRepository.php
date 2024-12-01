<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\Cart;
use App\Domain\Repository\CartRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cart>
 */
class CartRepository extends ServiceEntityRepository implements CartRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    /**
     * @param ManagerRegistry $registry
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Cart::class);
        $this->entityManager = $entityManager;
    }

    /**
     * Recupera el Cart de base de datos a partir de su ID
     *
     * @param string $id
     * @return Cart|null
     */
    public function findById(string $id): ?Cart
    {
        return $this->find($id);
    }

    /**
     * Guarda en base de datos un objeto Cart
     *
     * @param Cart $cart
     * @return Cart
     */
    public function save(Cart $cart): Cart
    {
        $this->entityManager->persist($cart);
        $this->entityManager->flush();

        return $cart;
    }

    /**
     * Borra de base de datos un Cart a partir del propio objeto Cart
     *
     * @param Cart $cart
     * @return void
     */
    public function delete(Cart $cart): void
    {
        $this->entityManager->remove($cart);
        $this->entityManager->flush();
    }
}
