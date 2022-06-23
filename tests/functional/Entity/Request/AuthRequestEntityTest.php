<?php

declare(strict_types=1);

namespace functional\Entity\Request;

use App\Entity\Request\AuthRequestEntity;
use App\Exception\EntityValidationException;
use PHPUnit\Framework\TestCase;

final class AuthRequestEntityTest extends TestCase
{
    public const VALID_ITEM_1 = [
        'entity' => [
            'username' => 'User',
            'password' => 'password',
            'merchantId' => '1234',
        ],
        'expected' => [
            'Content-Type' => 'application/json',
            'Accept' =>  'application/json',
            'Authorization' => 'Basic VXNlcjpwYXNzd29yZA==',
        ],
    ];

    public const VALID_ITEM_2 = [
        'entity' => [
            'username' => 'Username1234',
            'password' => 'pass1234',
            'merchant_id' => '5678',
        ],
        'expected' => [
            'Content-Type' => 'application/json',
            'Accept' =>  'application/json',
            'Authorization' => 'Basic VXNlcm5hbWUxMjM0OnBhc3MxMjM0',
        ],
    ];

    public const VALID_ITEM_3 = [
        'entity' => [
            'username' => 'another username',
            'password' => 'another password',
            'merchantId' => '9876',
        ],
        'expected' => [
            'Content-Type' => 'application/json',
            'Accept' =>  'application/json',
            'Authorization' => 'Basic YW5vdGhlciB1c2VybmFtZTphbm90aGVyIHBhc3N3b3Jk',
        ],
    ];

    public const INVALID_ITEM_1 = [
        'entity' => [
            'username' => '',
            'password' => '',
            'merchantId' => '',
        ],
        'violations' => [
            'The value of username is too short. It should have 2 characters or more.',
            'The value of username should not be blank.',
            'The value of password is too short. It should have 2 characters or more.',
            'The value of password should not be blank.',
            'The value of merchant id is too short. It should have 2 characters or more.',
            'The value of merchant id should not be blank.',
        ],
    ];

    public const INVALID_ITEM_2 = [
        'entity' => [
            'username' => null,
            'password' => 'password',
            'merchantId' => '1234',
        ],
        'violations' => [
            'Cannot assign null to property App\Entity\Request\AuthRequestEntity::$username of type string',
        ],
    ];

    public const INVALID_ITEM_3 = [
        'entity' => [
            'username' => 'u',
            'password' => 'p',
            'merchantId' => '1',
        ],
        'violations' => [
            'The value of username is too short. It should have 2 characters or more.',
            'The value of password is too short. It should have 2 characters or more.',
            'The value of merchant id is too short. It should have 2 characters or more.',
        ],
    ];

    protected AuthRequestEntity $sut;

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testMakeWithoutDataWillWorkAsExpected(): void
    {
        $this->sut = AuthRequestEntity::make();
        $this->assertInstanceOf(AuthRequestEntity::class, $this->sut);
    }

    /**
     * @return iterable<array<array<string, array<string, string>>>>
     */
    public function getValidAuthRequestEntityData(): iterable
    {
        yield [ self::VALID_ITEM_1 ];
        yield [ self::VALID_ITEM_2 ];
        yield [ self::VALID_ITEM_3 ];
    }

    /**
     * @dataProvider getValidAuthRequestEntityData
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param array<array<string, string>> $data
     * @throws EntityValidationException
     * @return void
     */
    public function testMakeWithDataWillSucceedAndEntityWillWorkAsExpected(array $data): void
    {
        $this->sut = AuthRequestEntity::make($data['entity']);
        $this->assertEquals(
            $data['expected'],
            $this->sut->getWeGetFinancingRequest()
        );

        $merchantId = (true === isset($data['entity']['merchantId']))
            ? $data['entity']['merchantId']
            : $data['entity']['merchant_id'];

        $this->assertEquals(
            '/merchant/' . $merchantId . '/requests',
            $this->sut->getRequestNewLoanPath()
        );
    }

    /**
     * @return iterable<int, array<array<string, array<int|string, mixed>>>>
     */
    public function getInvalidAuthRequestEntityData(): iterable
    {
        yield [ self::INVALID_ITEM_1 ];
        yield [ self::INVALID_ITEM_2 ];
        yield [ self::INVALID_ITEM_3 ];
    }

    /**
     * @dataProvider getInvalidAuthRequestEntityData
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param array<array<string, array<int|string, string>>> $data
     * @return void
     */
    public function testMakeWithDataWillFailAsExpected(array $data): void
    {
        try {
            AuthRequestEntity::make($data['entity']);
        } catch (EntityValidationException $exception) {
            $this->assertSame($data['violations'], $exception->getViolations());
        }
    }
}
