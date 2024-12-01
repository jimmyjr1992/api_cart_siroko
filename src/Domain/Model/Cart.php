<?php

namespace App\Domain\Model;

use App\Infrastructure\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartRepository::class)]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $userId = null;

    /**
     * @var Collection<int, CartItem>
     */
    #[ORM\ManyToMany(targetEntity: CartItem::class, inversedBy: 'carts')]
    private Collection $cartItems;

    #[ORM\Column]
    private ?int $status = null;

    public function __construct()
    {
        $this->cartItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): static
    {
        if ($userId !== null && $userId < 0) {
            throw new \TypeError("UserId should be a integer greater than 0 or null.");
        }

        $this->userId = $userId;

        return $this;
    }

    /**
     * @return Collection<int, CartItem>
     */
    public function getCartItems(): Collection
    {
        return $this->cartItems;
    }

    public function addCartItem(CartItem $cartItem): static
    {
        if (!$this->cartItems->contains($cartItem)) {
            $this->cartItems->add($cartItem);
        }

        return $this;
    }

    public function removeCartItem(CartItem $cartItem): static
    {
        $this->cartItems->removeElement($cartItem);

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): static
    {
        $this->status = $status;

        return $this;
    }
}
