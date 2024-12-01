<?php

namespace App\Domain\Validator;

class CartItemValidator
{
    /**
     * Valida que el array contiene los datos necesarios para construir el CartItem
     *
     * @param array $data
     * @return bool
     */
    public function validateCreateData(array $data): bool
    {
        if (!isset($data['product_id']) || !is_int($data['product_id'])) {
            return false;
        }

        if (!isset($data['quantity']) || !is_int($data['quantity'])) {
            return false;
        }

        if (!isset($data['price']) || !is_float($data['price'])) {
            return false;
        }

        return true;
    }

    /**
     * Valida que el array contiene los datos necesarios para actualizar el CartItem
     *
     * @param array $data
     * @return bool
     */
    public function validateUpdateData(array $data): bool
    {
        if (!isset($data['cart_item_id']) || !is_int($data['cart_item_id'])) {
            return false;
        }

        if (!isset($data['quantity']) || !is_int($data['quantity']) || $data['quantity'] <= 0) {
            return false;
        }

        return true;
    }

    /**
     * Valida que el array contiene los datos necesarios para eliminar el CartItem
     *
     * @param array $data
     * @return bool
     */
    public function validateDeleteData(array $data): bool
    {
        if (!isset($data['cart_item_id']) || !is_int($data['cart_item_id'])) {
            return false;
        }

        return true;
    }
}