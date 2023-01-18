<?php

declare(strict_types=1);

namespace Functional\Entity;

use PHPUnit\Framework\TestCase;
use WeGetFinancing\SDK\Entity\AuthEntity;
use WeGetFinancing\SDK\Exception\EntityValidationException;

final class AuthEntityTest extends TestCase
{
    use EntityValidationErrorsMapperTrait;

    public const VALID_ITEM_1 = [
        'entity' => [
            'username' => 'User',
            'password' => 'password',
            'merchantId' => '1234',
            'url' => 'https://valid.url.com',
        ],
        'expected' => [
            'username' => 'User',
            'password' => 'password',
            'merchantId' => '1234',
            'url' => 'https://valid.url.com',
        ],
    ];

    public const VALID_ITEM_2 = [
        'entity' => [
            'username' => 'Username1234',
            'password' => 'pass1234',
            'merchant_id' => '5678',
            'url' => 'https://api.sandbox.wegetfinancing.com',
        ],
        'expected' => [
            'username' => 'Username1234',
            'password' => 'pass1234',
            'merchantId' => '5678',
            'url' => 'https://api.sandbox.wegetfinancing.com',
        ],
    ];

    public const INVALID_ITEM_1 = [
        'entity' => [
            'username' => '',
            'password' => '',
            'merchantId' => '',
            'url' => '',
        ],
        'violations' => [
            7 => [
                'The value of username is too short. It should have 2 characters or more.',
                'The value of username should not be blank.',
                'The value of password is too short. It should have 2 characters or more.',
                'The value of password should not be blank.',
                'The value of merchant id is too short. It should have 2 characters or more.',
                'The value of merchant id should not be blank.',
                'The value of url should not be blank.',
            ],
            8 => [
                'The value of username is too short. It should have 2 characters or more.',
                'The value of username should not be blank.',
                'The value of password is too short. It should have 2 characters or more.',
                'The value of password should not be blank.',
                'The value of merchant id is too short. It should have 2 characters or more.',
                'The value of merchant id should not be blank.',
                'The value of url should not be blank.',
            ],
        ],
    ];

    public const INVALID_ITEM_2 = [
        'entity' => [
            'username' => null,
            'password' => 'password',
            'merchantId' => '1234',
        ],
        'violations' => [
            7 => [ 'Typed property WeGetFinancing\SDK\Entity\AuthEntity::$username must be string, null used' ],
            8 => [ 'Cannot assign null to property WeGetFinancing\SDK\Entity\AuthEntity::$username of type string' ],
        ],
    ];

    public const INVALID_ITEM_3 = [
        'entity' => [
            'username' => 'u',
            'password' => 'p',
            'merchantId' => '1',
            'url' => 'http://invalid.protocol.com',
        ],
        'violations' => [
            7 => [
                'The value of username is too short. It should have 2 characters or more.',
                'The value of password is too short. It should have 2 characters or more.',
                'The value of merchant id is too short. It should have 2 characters or more.',
                'The value of url is not a valid URL.',
            ],
            8 => [
                'The value of username is too short. It should have 2 characters or more.',
                'The value of password is too short. It should have 2 characters or more.',
                'The value of merchant id is too short. It should have 2 characters or more.',
                'The value of url is not a valid URL.',
            ],
        ],
    ];

    protected AuthEntity $sut;

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testMakeWithoutDataWillWorkAsExpected(): void
    {
        $this->sut = AuthEntity::make();
        $this->assertInstanceOf(AuthEntity::class, $this->sut);
    }

    /**
     * @return iterable<array<array<string, array<string, string>>>>
     */
    public function getValidAuthEntityData(): iterable
    {
        yield [ self::VALID_ITEM_1 ];
        yield [ self::VALID_ITEM_2 ];
    }

    /**
     * @dataProvider getValidAuthEntityData
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param array<array<string, string>> $data
     * @throws EntityValidationException
     * @return void
     */
    public function testMakeWithDataWillSucceedAndEntityWillWorkAsExpected(array $data): void
    {
        $this->sut = AuthEntity::make($data['entity']);
        $this->assertEquals(
            $data['expected']['username'],
            $this->sut->getUsername()
        );
        $this->assertEquals(
            $data['expected']['password'],
            $this->sut->getPassword()
        );
        $this->assertEquals(
            $data['expected']['merchantId'],
            $this->sut->getMerchantId()
        );
        $this->assertEquals(
            $data['expected']['url'],
            $this->sut->getUrl()
        );
    }

    /**
     * @return iterable<int, array<array<string, array<int|string, mixed>>>>
     */
    public function getInvalidAuthEntityData(): iterable
    {
        yield [ self::INVALID_ITEM_1 ];
        yield [ self::INVALID_ITEM_2 ];
        yield [ self::INVALID_ITEM_3 ];
    }

    /**
     * @dataProvider getInvalidAuthEntityData
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param mixed[] $data
     * @return void
     */
    public function testMakeWithDataWillFailAsExpected(array $data): void
    {
        try {
            AuthEntity::make($data['entity']);
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
