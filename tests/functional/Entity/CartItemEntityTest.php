<?php

declare(strict_types=1);

namespace functional\Entity;

use App\Entity\CartItemEntity;
use App\Exception\EntityValidationException;
use PHPUnit\Framework\TestCase;

/**
 * @functional
 */
final class CartItemEntityTest extends TestCase
{
    protected CartItemEntity $sut;

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testMakeWithoutDataWillWorkAsExpected(): void
    {
        $this->sut = CartItemEntity::make();
        $this->assertInstanceOf(CartItemEntity::class, $this->sut);
    }

    /**
     * @return iterable<array<array<string, array<string, string|int|float>>>>
     */
    public function getValidCartItemEntityData(): iterable
    {
        yield [[
            "entity" => [
                "sku" => "SKU_CODE_001",
                "displayName" => "Cart product 001",
                "unitPrice" => "10.0",
                "quantity" => 1,
                "unitTax" => 21.0,
                "category" => "CAT_A",
            ],
            "expected" => [
                "sku" => "SKU_CODE_001",
                "display_name" => "Cart product 001",
                "unit_price" => "10.00",
                "quantity" => 1,
                "unit_tax" => "21.00",
                "category" => "CAT_A",
            ],
        ]];
        yield [[
            "entity" => [
                "sku" => "02",
                "displayName" => "Cart product 002",
                "unitPrice" => 256,
                "quantity" => 6,
                "unitTax" => 0.75,
                "category" => "CAT_A",
            ],
            "expected" => [
                "sku" => "02",
                "display_name" => "Cart product 002",
                "unit_price" => "256.00",
                "quantity" => 6,
                "unit_tax" => "0.75",
                "category" => "CAT_A",
            ],
        ]];
        yield [[
            "entity" => [
                "sku" => "ZeroThree",
                "displayName" => "Cart product zero zero three",
                "unitPrice" => 8.86432,
                "quantity" => 9,
                "unitTax" => 134.6,
            ],
            "expected" => [
                "sku" => "ZeroThree",
                "display_name" => "Cart product zero zero three",
                "unit_price" => "8.86",
                "quantity" => 9,
                "unit_tax" => "134.60",
            ],
        ]];
    }

    /**
     * @dataProvider getValidCartItemEntityData
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param array<array<string, array<int|string, string>>> $data
     * @throws EntityValidationException
     * @return void
     */
    public function testMakeWithDataWillSucceedAsExpected(array $data): void
    {
        $this->sut = CartItemEntity::make($data['entity']);
        $this->assertSame($data['expected'], $this->sut->getWeGetFinancingRequest());
    }

    /**
     * @return iterable<int, array<array<string, array<int|string, mixed>>>>
     */
    public function getInvalidCartItemEntityData(): iterable
    {
        yield [[
            'entity' => [
                'sku' => '',
                'displayName' => '1',
                'unitPrice' => '10.0',
                'quantity' => -2,
                'unitTax' => 0.0,
            ],
            'violations' => [
                'The value of sku should not be blank.',
                'The value of display name  is too short. It should have 2 characters or more.',
                'The value of quantity should be positive.',
            ],
        ]];
        yield [[
            'entity' => [
                'sku' => '',
                'displayName' => '1',
                'unitPrice' => '0',
                'quantity' => -2,
                'unitTax' => 0.0,
            ],
            'violations' => [
                'Unit Price value should not be equal or less than zero.',
            ],
        ]];
        yield [[
            'entity' => [
                'sku' => false,
                'displayName' => '1',
                'unitPrice' => '10',
                'quantity' => -2,
                'unitTax' => null,
            ],
            'violations' => [
                'Cannot assign bool to property App\Entity\CartItemEntity::$sku of type string',
            ],
        ]];
        yield [[
            'entity' => [
                'sku' => "correct",
                'displayName' => '1',
                'unitPrice' => '10',
                'quantity' => -2,
                'unitTax' => null,
            ],
            'violations' => [
                'Unit Tax Cannot assign null to property App\Entity\MoneyEntity::$value of type string',
            ],
        ]];
    }

    /**
     * @dataProvider getInvalidCartItemEntityData
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param array<array<string, array<int|string, string>>> $data
     * @return void
     */
    public function testMakeWithDataWillFailAsExpected(array $data): void
    {
        try {
            CartItemEntity::make($data["entity"]);
        } catch (EntityValidationException $exception) {
            $this->assertSame($data["violations"], $exception->getViolations());
        }
    }
}
