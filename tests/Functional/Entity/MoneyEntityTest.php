<?php

declare(strict_types=1);

namespace Functional\Entity;

use PHPUnit\Framework\TestCase;
use WeGetFinancing\SDK\Entity\MoneyEntity;
use WeGetFinancing\SDK\Exception\EntityValidationException;

/**
 * @functional
 */
final class MoneyEntityTest extends TestCase
{
    use EntityValidationErrorsMapperTrait;

    public const VALID_ITEM_1 = [
        'entity' => [
            'value' => '12.56',
            'isZeroAllowed' => false,
        ],
        'expected' => '12.56',
    ];

    public const VALID_ITEM_2 = [
        'entity' => [
            'value' => 5,
            'isZeroAllowed' => true,
        ],
        'expected' => '5.00',
    ];

    public const VALID_ITEM_3 = [
        'entity' => [
            'value' => 7.0,
        ],
        'expected' => '7.00',
    ];

    public const VALID_ITEM_4 = [
        'entity' => [
            'value' => '5.1',
            'isZeroAllowed' => true,
        ],
        'expected' => '5.10',
    ];

    public const VALID_ITEM_5 = [
        'entity' => [
            'value' => 4.2,
        ],
        'expected' => '4.20',
    ];

    public const VALID_ITEM_6 = [
        'entity' => [
            'value' => 66.999999563,
        ],
        'expected' => '66.99',
    ];

    public const VALID_ITEM_7 = [
        'entity' => [
            'value' => 0.0,
            'isZeroAllowed' => true,
        ],
        'expected' => '0.00',
    ];

    public const VALID_ITEM_8 = [
        'entity' => [
            'value' => 0,
            'isZeroAllowed' => true,
        ],
        'expected' => '0.00',
    ];

    public const VALID_ITEM_9 = [
        'entity' => [
            'value' => '0',
            'isZeroAllowed' => true,
        ],
        'expected' => '0.00',
    ];

    public const INVALID_ITEM_1 = [
        'entity' => [
            'value' => '-12.56',
        ],
        'violations' => [
            'The money entity named unknown generated an error, The value should be either positive or zero if allowed.'
        ],
    ];

    public const INVALID_ITEM_2 = [
        'entity' => [
            'value' => -9,
        ],
        'violations' => [
            'The money entity named unknown generated an error, The value should be either positive or zero if allowed.'
        ],
    ];

    public const INVALID_ITEM_3 = [
        'entity' => [
            'value' => 'A',
        ],
        'violations' => [
            'The money entity named unknown generated an error, The value "A" is not a valid numeric.',
            'The money entity named unknown generated an error, value should not be equal or less than zero.'
        ],
    ];

    public const INVALID_ITEM_4 = [
        'entity' => [
            'name' => 'Tax',
            'value' => '4.b',
        ],
        'violations' => [
            'The money entity named Tax generated an error, The value "4.b" is not a valid numeric.',
            'The money entity named Tax generated an error, value should not be equal or less than zero.'
        ],
    ];

    public const INVALID_ITEM_5 = [
        'entity' => [
            'name' => 'The money',
            'value' => '',
        ],
        'violations' => [
            'The money entity named The money generated an error, The value "" is not a valid numeric.',
            'The money entity named The money generated an error, The value should be either positive or zero if allowed.',
            'The money entity named The money generated an error, The value should not be blank.',
            'The money entity named The money generated an error, value should not be equal or less than zero.'
        ],
    ];

    public const INVALID_ITEM_6 = [
        'entity' => [
            'name' => 'Unit Price',
            'value' => null,
        ],
        'violations' => [
            'The money entity named Unit Price generated an error, The value should not be null.',
            'The money entity named Unit Price generated an error, value should not be equal or less than zero.'
        ],
    ];

    public const INVALID_ITEM_7 = [
        'entity' => [
            'name' => 'Amount',
            'value' => 'no',
        ],
        'violations' => [
            'The money entity named Amount generated an error, The value "no" is not a valid numeric.',
            'The money entity named Amount generated an error, value should not be equal or less than zero.'
        ],
    ];

    public const INVALID_ITEM_8 = [
        'entity' => [
            'value' => true,
        ],
        'violations' => [
            'The money entity named unknown generated an error, The value true is not a valid numeric.',
            'The money entity named unknown generated an error, value should not be equal or less than zero.'
        ],
    ];

    public const INVALID_ITEM_9 = [
        'entity' => [
            'name' => 'NAME',
            'value' => true,
        ],
        'violations' => [
            'The money entity named NAME generated an error, The value true is not a valid numeric.',
            'The money entity named NAME generated an error, value should not be equal or less than zero.'
        ],
    ];

    public const INVALID_ITEM_10 = [
        'entity' => [
            'isZeroAllowed' => false,
            'value' => 0,
        ],
        'violations' => [
            'The money entity named unknown generated an error, value should not be equal or less than zero.'
        ],
    ];

    public const INVALID_ITEM_11 = [
        'entity' => [
            'isZeroAllowed' => false,
            'name' => 'TEST',
            'value' => 0.0,
        ],
        'violations' => [
            'The money entity named TEST generated an error, value should not be equal or less than zero.'
        ],
    ];

    public const INVALID_ITEM_12 = [
        'entity' => [
            'isZeroAllowed' => false,
            'value' => "0",
        ],
        'violations' => [
            'The money entity named unknown generated an error, value should not be equal or less than zero.'
        ],
    ];

    protected MoneyEntity $sut;

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testMakeWithoutDataWillWorkAsExpected(): void
    {
        $this->sut = MoneyEntity::make();
        $this->assertInstanceOf(MoneyEntity::class, $this->sut);
    }

    /**
     * @return iterable<int, array<array<string, string|array<string, mixed>>>>
     */
    public function getValidMoneyEntityData(): iterable
    {
        yield [ self::VALID_ITEM_1 ];
        yield [ self::VALID_ITEM_2 ];
        yield [ self::VALID_ITEM_3 ];
        yield [ self::VALID_ITEM_4 ];
        yield [ self::VALID_ITEM_5 ];
        yield [ self::VALID_ITEM_6 ];
        yield [ self::VALID_ITEM_7 ];
        yield [ self::VALID_ITEM_8 ];
        yield [ self::VALID_ITEM_9 ];
    }

    /**
     * @dataProvider getValidMoneyEntityData
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param array<array<string, string|array<string, float>>> $data
     * @throws EntityValidationException
     * @return void
     */
    public function testMakeWithDataWillSucceedAsExpected(array $data): void
    {
        $this->sut = MoneyEntity::make($data['entity']);
        $this->assertSame($data['expected'], $this->sut->getValue());
    }

    /**
     * @return iterable<int, array<array<string, string|array<int|string, mixed>>>>
     */
    public function getInvalidMoneyEntityData(): iterable
    {
        yield [ self::INVALID_ITEM_1 ];
        yield [ self::INVALID_ITEM_2 ];
        yield [ self::INVALID_ITEM_3 ];
        yield [ self::INVALID_ITEM_4 ];
        yield [ self::INVALID_ITEM_5 ];
        yield [ self::INVALID_ITEM_6 ];
        yield [ self::INVALID_ITEM_7 ];
        yield [ self::INVALID_ITEM_8 ];
        yield [ self::INVALID_ITEM_9 ];
        yield [ self::INVALID_ITEM_10 ];
        yield [ self::INVALID_ITEM_11 ];
        yield [ self::INVALID_ITEM_12 ];
    }

    /**
     * @dataProvider getInvalidMoneyEntityData
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param mixed[] $data
     * @return void
     */
    public function testMakeWithDataWillFailAsExpected(array $data): void
    {
        try {
            MoneyEntity::make($data['entity']);
        } catch (EntityValidationException $exception) {
            $violations = $this->getViolationMessages($exception);
            $this->assertSame($data['violations'], $violations);
        }
    }
}
