<?php

namespace App\Domain\ValueObject;

use InvalidArgumentException;

final class CartId
{
    private string $value;

    private function __construct(string $value)
    {
        if (empty($value) || !ctype_digit($value)) {
            throw new InvalidArgumentException("Invalid Cart Id");
        }

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}