<?php

declare(strict_types=1);

namespace functional\Entity;

use App\Entity\AddressEntity;
use App\Exception\EntityValidationException;
use PHPUnit\Framework\TestCase;

class AddressEntityTest extends TestCase
{
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
     * @return iterable<array<array<string, string>>>
     */
    public function getValidAddressEntityData(): iterable
    {
        yield [[
            "street1" => "15th Fantasy Street",
            "city" => "New York",
            "state" => "NY",
            "zipcode" => "66607",
        ]];
        yield [[
            "street1" => "15th Fantasy Street",
            "city" => "Redmond",
            "state" => "WA",
            "zipcode" => "55555-4444",
        ]];
    }

    /**
     * @dataProvider getValidAddressEntityData
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param array<array<string, array<int|string, string>>> $data
     * @return void
     * @throws EntityValidationException
     */
    public function testMakeWithDataWillSucceedAsExpected(array $data): void
    {
        $this->sut = AddressEntity::make($data);

        $this->assertSame($data["street1"], $this->sut->street1);
        $this->assertSame($data["city"], $this->sut->city);
        $this->assertSame($data["state"], $this->sut->state);
        $this->assertSame($data["zipcode"], $this->sut->zipcode);

        $this->assertSame(
            $data,
            $this->sut->getWeGetFinancingRequest()
        );
    }

    /**
     * @return iterable<int, array<array<string, array<int|string, mixed>>>>
     */
    public function getInvalidAddressEntityData(): iterable
    {
        yield [[
            'entity' => [
                'street1' => '',
                'city' => '',
                'state' => '',
                'zipcode' => '',
            ],
            'violations' => [
                'The value of street1 is too short. It should have 2 characters or more.',
                'The value of street1 should not be blank.',
                'The value of city is too short. It should have 2 characters or more.',
                'The value of city should not be blank.',
                'The value of state should have exactly 2 characters.',
                'The value of state should not be blank.',
                'The value of zipcode should not be blank.',
            ],
        ]];
        yield [[
            'entity' => [
                'street1' => '15th Fantasy Street',
                'city' => 'Redmond',
                'state' => 'WAA',
                'zipcode' => '666666',
            ],
            'violations' => [
                'The value of state should have exactly 2 characters.',
                'The value of zipcode should contain only 5 numbers optionally followed by a dash and 4 numbers.',
            ],
        ]];
        yield [[
            'entity' => [
                'street1' => '1',
                'city' => 'R',
                'state' => 'W',
                'zipcode' => '55555-55555',
            ],
            'violations' => [
                'The value of street1 is too short. It should have 2 characters or more.',
                'The value of city is too short. It should have 2 characters or more.',
                'The value of state should have exactly 2 characters.',
                'The value of zipcode should contain only 5 numbers optionally followed by a dash and 4 numbers.',
            ],
        ]];
        yield [[
            'entity' => [
                'street1' => '15th Fantasy Street',
                'city' => 'New York',
            ],
            'violations' => [
                'The value of state should not be blank.',
                'The value of zipcode should not be blank.',
            ],
        ]];
        yield [[
            'entity' => [
                'state' => 'NY',
                'zipcode' => '66607',
            ],
            'violations' => [
                'The value of street1 should not be blank.',
                'The value of city should not be blank.',
            ],
        ]];
        yield [[
            'entity' => [
                'street1' => null,
                'city' => 7.9,
                'state' => 'NY',
                'zipcode' => '66607'
            ],
            'violations' => [
                'Cannot assign null to property App\Entity\AddressEntity::$street1 of type string'
            ],
        ]];
        yield [[
            'entity' => [
                'street1' => 7.5,
                'city' => 'New York',
                'state' => 'NY',
                'zipcode' => '66607'
            ],
            'violations' => [
                'Cannot assign float to property App\Entity\AddressEntity::$street1 of type string'
            ],
        ]];
        yield [[
            'entity' => [
                'street1' => '15th Fantasy Street',
                'city' => null,
                'state' => 'NY',
                'zipcode' => '66607',
            ],
            'violations' => [
                'Cannot assign null to property App\Entity\AddressEntity::$city of type string'
            ],
        ]];
        yield [[
            'entity' => [
                'street1' => '15th Fantasy Street',
                'city' => 'New York',
                'state' => null,
                'zipcode' => '66607',
            ],
            'violations' => [
                'Cannot assign null to property App\Entity\AddressEntity::$state of type string'
            ],
        ]];
        yield [[
            'entity' => [
                'street1' => '15th Fantasy Street',
                'city' => 'New York',
                'state' => 'NY',
                'zipcode' => 1,
            ],
            'violations' => [
                'Cannot assign int to property App\Entity\AddressEntity::$zipcode of type string'
            ],
        ]];
    }

    /**
     * @dataProvider getInvalidAddressEntityData
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param array<array<string, array<int|string, string>>> $data
     * @return void
     */
    public function testMakeWithDataWillFailAsExpected(array $data): void
    {
        try {
            AddressEntity::make($data["entity"]);
        } catch (EntityValidationException $exception) {
            $this->assertSame($data["violations"], $exception->getViolations());
        }
    }
}
