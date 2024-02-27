<?php

declare(strict_types=1);

namespace Functional\Entity\Request;

use Functional\Entity\EntityValidationErrorsMapperTrait;
use PHPUnit\Framework\TestCase;
use WeGetFinancing\SDK\Entity\Request\CartItemEntity;
use WeGetFinancing\SDK\Exception\EntityValidationException;

/**
 * @functional
 */
final class CartItemEntityTest extends TestCase
{
    use EntityValidationErrorsMapperTrait;

    public const VALID_ITEM_1 = [
        'entity' => [
            'sku' => 'SKU_CODE_001',
            'displayName' => 'Cart product 001',
            'unitPrice' => '10.0',
            'quantity' => 1,
            'unitTax' => 21.0,
            'category' => 'CAT_A',
        ],
        'expected' => [
            'sku' => 'SKU_CODE_001',
            'display_name' => 'Cart product 001',
            'unit_price' => '10.00',
            'quantity' => 1,
            'unit_tax' => '21.00',
            'category' => 'CAT_A',
        ],
    ];

    public const VALID_ITEM_2 = [
        'entity' => [
            'sku' => '02',
            'displayName' => 'Cart product 002',
            'unitPrice' => 256,
            'quantity' => 6,
            'unitTax' => 0.75,
            'category' => 'CAT_A',
        ],
        'expected' => [
            'sku' => '02',
            'display_name' => 'Cart product 002',
            'unit_price' => '256.00',
            'quantity' => 6,
            'unit_tax' => '0.75',
            'category' => 'CAT_A',
        ],
    ];

    public const VALID_ITEM_3 = [
        'entity' => [
            'sku' => 'ZeroThree',
            'displayName' => 'Cart product zero zero three',
            'unitPrice' => 8.86432,
            'quantity' => 9,
            'unitTax' => 134.6,
        ],
        'expected' => [
            'sku' => 'ZeroThree',
            'display_name' => 'Cart product zero zero three',
            'unit_price' => '8.86',
            'quantity' => 9,
            'unit_tax' => '134.60',
        ],
    ];

    public const INVALID_ITEM_1 = [
        'entity' => [
            'sku' => '',
            'displayName' => '',
            'unitPrice' => '',
            'quantity' => '',
            'unitTax' => '',
        ],
        'violations' => [
            'The value of sku should not be blank.',
            'The value of display name should not be blank.',
            'The value of display name is too short. It should have 2 characters or more.',
            'The value of unit price should not be null.',
            'The value of quantity should be positive.',
            'The value of quantity - "" - is not a valid integer.',
            'The value of unit tax should not be null.',
            'The money entity named Unit Price generated an error, The value "" is not a valid numeric.',
            'The money entity named Unit Price generated an error, The value should be either positive or zero if allowed.',
            'The money entity named Unit Price generated an error, The value should not be blank.',
            'The money entity named Unit Price generated an error, value should not be equal or less than zero.',
            'The money entity named Unit Tax generated an error, The value "" is not a valid numeric.',
            'The money entity named Unit Tax generated an error, The value should be either positive or zero if allowed.',
            'The money entity named Unit Tax generated an error, The value should not be blank.',
        ],
    ];

    public const INVALID_ITEM_2 = [
        'entity' => [
            'sku' => null,
            'displayName' => null,
            'unitPrice' => null,
            'quantity' => null,
            'unitTax' => null,
        ],
        'violations' => [
            'The value of sku should not be null.',
            'The value of display name should not be null.',
            'The value of unit price should not be null.',
            'The value of unit tax should not be null.',
            'The money entity named Unit Price generated an error, The value should not be null.',
            'The money entity named Unit Price generated an error, value should not be equal or less than zero.',
            'The money entity named Unit Tax generated an error, The value should not be null.',
        ],
    ];

    public const INVALID_ITEM_3 = [
        'entity' => [
            'sku' => false,
            'displayName' => '1',
            'unitPrice' => '10',
            'quantity' => -2,
            'unitTax' => null,
        ],
        'violations' => [
            'The value of sku should not be blank.',
            'The value of sku - false - is not a valid string.',
            'The value of display name is too short. It should have 2 characters or more.',
            'The value of quantity should be positive.',
            'The value of unit tax should not be null.',
            'The money entity named Unit Tax generated an error, The value should not be null.',
        ],
    ];

    public const INVALID_ITEM_4 = [
        'entity' => [
            'sku' => 'correct',
            'displayName' => '1',
            'unitPrice' => '10',
            'quantity' => -2,
            'unitTax' => null,
        ],
        'violations' => [
            'The value of display name is too short. It should have 2 characters or more.',
            'The value of quantity should be positive.',
            'The value of unit tax should not be null.',
            'The money entity named Unit Tax generated an error, The value should not be null.',
        ],
    ];

    public const INVALID_ITEM_5 = [
        'entity' => [
            'sku' => '',
            'displayName' => '1',
            'unitPrice' => '10.0',
            'quantity' => -2,
            'unitTax' => 0.0,
        ],
        'violations' => [
                'The value of sku should not be blank.',
                'The value of display name is too short. It should have 2 characters or more.',
                'The value of quantity should be positive.',
        ],
    ];

    public const INVALID_ITEM_6 = [
        'entity' => [
            'sku' => '',
            'displayName' => '1',
            'unitPrice' => '0',
            'quantity' => -2,
            'unitTax' => 0.0,
        ],
        'violations' => [
            'The value of sku should not be blank.',
            'The value of display name is too short. It should have 2 characters or more.',
            'The value of unit price should not be null.',
            'The value of quantity should be positive.',
            'The money entity named Unit Price generated an error, value should not be equal or less than zero.',
        ],
    ];

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
        yield [ self::VALID_ITEM_1 ];
        yield [ self::VALID_ITEM_2 ];
        yield [ self::VALID_ITEM_3 ];
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
        yield [ self::INVALID_ITEM_1 ];
        yield [ self::INVALID_ITEM_2 ];
        yield [ self::INVALID_ITEM_3 ];
        yield [ self::INVALID_ITEM_4 ];
        yield [ self::INVALID_ITEM_5 ];
        yield [ self::INVALID_ITEM_6 ];
    }

    /**
     * @dataProvider getInvalidCartItemEntityData
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param mixed[] $data
     * @return void
     */
    public function testMakeWithDataWillFailAsExpected(array $data): void
    {
        try {
            CartItemEntity::make($data['entity']);
        } catch (EntityValidationException $exception) {
            $violations = $this->getViolationMessages($exception);
            $this->assertSame($data['violations'], $violations);
        }
    }
}
