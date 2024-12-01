<?php

namespace App\Tests\Domain\Validator;

use App\Domain\Validator\CartItemValidator;
use PHPUnit\Framework\TestCase;

class CartItemValidatorTest extends TestCase
{
    private CartItemValidator $cartItemValidator;

    protected function setUp(): void
    {
        $this->cartItemValidator = new CartItemValidator();
    }

    public function testValidateCreateDataValid(): void
    {
        $data = [
            'product_id' => 123,
            'quantity' => 2,
            'price' => 10.00,
        ];

        $isValid = $this->cartItemValidator->validateCreateData($data);
        $this->assertTrue($isValid);
    }

    public function testValidateCreateDataInvalidProductId(): void
    {
        $data = [
            'product_id' => 'abc',
            'quantity' => 2,
            'price' => 10.00,
        ];

        $isValid = $this->cartItemValidator->validateCreateData($data);
        $this->assertFalse($isValid);
    }

    public function testValidateCreateDataInvalidQuantity(): void
    {
        $data = [
            'product_id' => 123,
            'quantity' => 'dos',
            'price' => 10.00,
        ];

        $isValid = $this->cartItemValidator->validateCreateData($data);
        $this->assertFalse($isValid);
    }

    public function testValidateCreateDataInvalidPrice(): void
    {
        $data = [
            'product_id' => 123,
            'quantity' => 2,
            'price' => 'diez',
        ];

        $isValid = $this->cartItemValidator->validateCreateData($data);

        $this->assertFalse($isValid);
    }

    public function testValidateCreateDataMissingProductId(): void
    {
        $data = [
            'quantity' => 2,
            'price' => 10.00,
        ];

        $isValid = $this->cartItemValidator->validateCreateData($data);

        $this->assertFalse($isValid);
    }

    public function testValidateCreateDataMissingQuantity(): void
    {
        $data = [
            'product_id' => 123,
            'price' => 10.00,
        ];

        $isValid = $this->cartItemValidator->validateCreateData($data);

        $this->assertFalse($isValid);
    }

    public function testValidateCreateDataMissingPrice(): void
    {
        $data = [
            'product_id' => 123,
            'quantity' => 2,
        ];

        $isValid = $this->cartItemValidator->validateCreateData($data);

        $this->assertFalse($isValid);
    }

    public function testValidateUpdateDataValid(): void
    {
        $data = [
            'cart_item_id' => 456,
            'quantity' => 3,
        ];

        $isValid = $this->cartItemValidator->validateUpdateData($data);

        $this->assertTrue($isValid);
    }

    public function testValidateUpdateDataInvalidCartItemId(): void
    {
        $data = [
            'cart_item_id' => 'abc',
            'quantity' => 3,
        ];

        $isValid = $this->cartItemValidator->validateUpdateData($data);

        $this->assertFalse($isValid);
    }

    public function testValidateUpdateDataInvalidQuantity(): void
    {
        $data = [
            'cart_item_id' => 456,
            'quantity' => 'tres',
        ];

        $isValid = $this->cartItemValidator->validateUpdateData($data);

        $this->assertFalse($isValid);
    }

    public function testValidateUpdateDataMissingCartItemId(): void
    {
        $data = [
            'quantity' => 3,
        ];

        $isValid = $this->cartItemValidator->validateUpdateData($data);

        $this->assertFalse($isValid);
    }

    public function testValidateUpdateDataMissingQuantity(): void
    {
        $data = [
            'cart_item_id' => 456,
        ];

        $isValid = $this->cartItemValidator->validateUpdateData($data);

        $this->assertFalse($isValid);
    }

    // Tests para validateDeleteData()
    public function testValidateDeleteDataValid(): void
    {
        $data = [
            'cart_item_id' => 789,
        ];

        $isValid = $this->cartItemValidator->validateDeleteData($data);

        $this->assertTrue($isValid);
    }

    public function testValidateDeleteDataInvalidCartItemId(): void
    {
        $data = [
            'cart_item_id' => 'abc',
        ];

        $isValid = $this->cartItemValidator->validateDeleteData($data);

        $this->assertFalse($isValid);
    }

    public function testValidateDeleteDataMissingCartItemId(): void
    {
        $data = [];

        $isValid = $this->cartItemValidator->validateDeleteData($data);

        $this->assertFalse($isValid);
    }
}
