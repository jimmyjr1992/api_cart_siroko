<?php

namespace App\Domain\ValueObject;

use InvalidArgumentException;

final class CartId
{
    private int $value;

    /**
     * @param int $value
     */
    public function __construct(int $value)
    {
        if (empty($value) || $value < 0) {
            throw new InvalidArgumentException("Invalid Cart Id");
        }

        $this->value = $value;
    }

    /**
     * Devuelve el valor en bruto
     *
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * Devuelve el valor en formato string
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }
}