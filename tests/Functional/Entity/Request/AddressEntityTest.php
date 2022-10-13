<?php

declare(strict_types=1);

namespace Functional\Entity\Request;

use Functional\Entity\EntityValidationErrorsMapperTrait;
use PHPUnit\Framework\TestCase;
use WeGetFinancing\SDK\Entity\Request\AddressEntity;
use WeGetFinancing\SDK\Exception\EntityValidationException;

/**
 * @functional
 */
final class AddressEntityTest extends TestCase
{
    use EntityValidationErrorsMapperTrait;

    public const VALID_ITEM_1 = [
        'entity' => [
            'street1' => '6 West End Ct',
            'city' => 'Long Branch',
            'state' => 'NJ',
            'zipcode' => '07740',
        ],
        'expected' => [
            'street1' => '6 West End Ct',
            'city' => 'Long Branch',
            'state' => 'NJ',
            'zipcode' => '07740',
        ],
    ];

    public const VALID_ITEM_2 = [
        'entity' => [
            'street1' => '2 Industrial Way West Suite 201',
            'city' => 'Eatontown',
            'state' => 'NJ',
            'zipcode' => '07724',
        ],
        'expected' => [
            'street1' => '2 Industrial Way West Suite 201',
            'city' => 'Eatontown',
            'state' => 'NJ',
            'zipcode' => '07724',
        ],
    ];

    public const VALID_ITEM_3 = [
        'entity' => [
            'street1' => '15th Fantasy Street',
            'city' => 'New York',
            'state' => 'NY',
            'zipcode' => '66607',
        ],
        'expected' => [
            'street1' => '15th Fantasy Street',
            'city' => 'New York',
            'state' => 'NY',
            'zipcode' => '66607',
        ],
    ];

    public const VALID_ITEM_4 = [
        'entity' => [
            'street1' => '15th Fantasy Street',
            'city' => 'New York',
            'state' => 'NY',
            'zipcode' => '66607',
        ],
        'expected' => [
            'street1' => '15th Fantasy Street',
            'city' => 'New York',
            'state' => 'NY',
            'zipcode' => '66607',
        ],
    ];

    public const INVALID_ITEM_1 = [
        'entity' => [
            'street1' => '',
            'city' => '',
            'state' => '',
            'zipcode' => '',
        ],
        'violations' => [
            7 => [
                'The value of street1 is too short. It should have 2 characters or more.',
                'The value of street1 should not be blank.',
                'The value of city is too short. It should have 2 characters or more.',
                'The value of city should not be blank.',
                'The value of state should have exactly 2 characters.',
                'The value of state should not be blank.',
                'The value of zipcode should not be blank.',
            ],
            8 => [
                'The value of street1 is too short. It should have 2 characters or more.',
                'The value of street1 should not be blank.',
                'The value of city is too short. It should have 2 characters or more.',
                'The value of city should not be blank.',
                'The value of state should have exactly 2 characters.',
                'The value of state should not be blank.',
                'The value of zipcode should not be blank.',
            ],
        ],
    ];

    public const INVALID_ITEM_2 = [
        'entity' => [
            'street1' => '15th Fantasy Street',
            'city' => 'Redmond',
            'state' => 'WAA',
            'zipcode' => '666666',
        ],
        'violations' => [
            7 => [
                'The value of state should have exactly 2 characters.',
                'The value of zipcode should contain only 5 numbers optionally followed by a dash and 4 numbers.',
            ],
            8 => [
                'The value of state should have exactly 2 characters.',
                'The value of zipcode should contain only 5 numbers optionally followed by a dash and 4 numbers.',
            ],
        ],
    ];

    public const INVALID_ITEM_3 = [
        'entity' => [
            'street1' => '1',
            'city' => 'R',
            'state' => 'W',
            'zipcode' => '55555-55555',
        ],
        'violations' => [
            7 => [
                'The value of street1 is too short. It should have 2 characters or more.',
                'The value of city is too short. It should have 2 characters or more.',
                'The value of state should have exactly 2 characters.',
                'The value of zipcode should contain only 5 numbers optionally followed by a dash and 4 numbers.',
            ],
            8 => [
                'The value of street1 is too short. It should have 2 characters or more.',
                'The value of city is too short. It should have 2 characters or more.',
                'The value of state should have exactly 2 characters.',
                'The value of zipcode should contain only 5 numbers optionally followed by a dash and 4 numbers.',
            ],
        ],
    ];

    public const INVALID_ITEM_4 = [
        'entity' => [
            'street1' => '15th Fantasy Street',
            'city' => 'New York',
        ],
        'violations' => [
            7 => [
                'The value of state should not be blank.',
                'The value of zipcode should not be blank.',
            ],
            8 => [
                'The value of state should not be blank.',
                'The value of zipcode should not be blank.',
            ],
        ],
    ];

    public const INVALID_ITEM_5 = [
        'entity' => [
            'state' => 'NY',
            'zipcode' => '66607',
        ],
        'violations' => [
            7 => [
                'The value of street1 should not be blank.',
                'The value of city should not be blank.',
            ],
            8 => [
                'The value of street1 should not be blank.',
                'The value of city should not be blank.',
            ],
        ],
    ];

    public const INVALID_ITEM_6 = [
        'entity' => [
            'street1' => null,
            'city' => 7.9,
            'state' => 'NY',
            'zipcode' => '66607',
        ],
        'violations' => [
            7 => [ 'Typed property WeGetFinancing\SDK\Entity\Request\AddressEntity::$street1 must be string, null used' ],
            8 => [ 'Cannot assign null to property WeGetFinancing\SDK\Entity\Request\AddressEntity::$street1 of type string' ],
        ],
    ];

    public const INVALID_ITEM_7 = [
        'entity' => [
            'street1' => 7.5,
            'city' => 'New York',
            'state' => 'NY',
            'zipcode' => '66607',
        ],
        'violations' => [
            7 => [ 'Typed property WeGetFinancing\SDK\Entity\Request\AddressEntity::$street1 must be string, float used' ],
            8 => [ 'Cannot assign float to property WeGetFinancing\SDK\Entity\Request\AddressEntity::$street1 of type string' ],
        ],
    ];

    public const INVALID_ITEM_8 = [
        'entity' => [
            'street1' => 7.5,
            'city' => 'New York',
            'state' => 'NY',
            'zipcode' => '66607',
        ],
        'violations' => [
            7 => [ 'Typed property WeGetFinancing\SDK\Entity\Request\AddressEntity::$street1 must be string, float used' ],
            8 => [ 'Cannot assign float to property WeGetFinancing\SDK\Entity\Request\AddressEntity::$street1 of type string' ],
        ],
    ];

    public const INVALID_ITEM_9 = [
        'entity' => [
            'street1' => '15th Fantasy Street',
            'city' => null,
            'state' => 'NY',
            'zipcode' => '66607',
        ],
        'violations' => [
            7 => [ 'Typed property WeGetFinancing\SDK\Entity\Request\AddressEntity::$city must be string, null used' ],
            8 => [ 'Cannot assign null to property WeGetFinancing\SDK\Entity\Request\AddressEntity::$city of type string' ],
        ],
    ];

    public const INVALID_ITEM_10 = [
        'entity' => [
            'street1' => '15th Fantasy Street',
            'city' => 'New York',
            'state' => null,
            'zipcode' => '66607',
        ],
        'violations' => [
            7 => [ 'Typed property WeGetFinancing\SDK\Entity\Request\AddressEntity::$state must be string, null used' ],
            8 => [ 'Cannot assign null to property WeGetFinancing\SDK\Entity\Request\AddressEntity::$state of type string' ],
        ],
    ];

    public const INVALID_ITEM_11 = [
        'entity' => [
            'street1' => '15th Fantasy Street',
            'city' => 'New York',
            'state' => 'NY',
            'zipcode' => 1,
        ],
        'violations' => [
            7 => [ 'Typed property WeGetFinancing\SDK\Entity\Request\AddressEntity::$zipcode must be string, int used' ],
            8 => [ 'Cannot assign int to property WeGetFinancing\SDK\Entity\Request\AddressEntity::$zipcode of type string' ],
        ],
    ];

    protected AddressEntity $sut;

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testMakeWithoutDataWillWorkAsExpected(): void
    {
        $this->sut = AddressEntity::make();
        $this->assertInstanceOf(AddressEntity::class, $this->sut);
    }

    /**
     * @return iterable<array<array<string, array<string, string>>>>
     */
    public function getValidAddressEntityData(): iterable
    {
        yield [ self::VALID_ITEM_1 ];
        yield [ self::VALID_ITEM_2 ];
        yield [ self::VALID_ITEM_3 ];
        yield [ self::VALID_ITEM_4 ];
    }

    /**
     * @dataProvider getValidAddressEntityData
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param array<array<string, array<int|string, string>>> $data
     * @throws EntityValidationException
     * @return void
     */
    public function testMakeWithDataWillSucceedAsExpected(array $data): void
    {
        $this->sut = AddressEntity::make($data['entity']);
        $this->assertSame(
            $data['expected'],
            $this->sut->getWeGetFinancingRequest()
        );
    }

    /**
     * @return iterable<int, array<array<string, array<int|string, mixed>>>>
     */
    public function getInvalidAddressEntityData(): iterable
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
    }

    /**
     * @dataProvider getInvalidAddressEntityData
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param mixed[] $data
     * @return void
     */
    public function testMakeWithDataWillFailAsExpected(array $data): void
    {
        try {
            AddressEntity::make($data['entity']);
        } catch (EntityValidationException $exception) {
            $violations = $this->getViolationMessages($exception);
            $this->assertSame(
                (version_compare(PHP_VERSION, '8.0.0', '<'))
                    ? $data['violations'][7]
                    : $data['violations'][8],
                $violations
            );
        }
    }
}
