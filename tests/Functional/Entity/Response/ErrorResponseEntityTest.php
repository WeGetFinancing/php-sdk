<?php

declare(strict_types=1);

namespace Functional\Entity\Response;

use App\Entity\Response\ErrorResponseEntity;
use App\Exception\EntityValidationException;
use PHPUnit\Framework\TestCase;

final class ErrorResponseEntityTest extends TestCase
{
    public const VALID_ITEM_1 = [
        'entity' => [
            'error' => 'invalid_parameters',
            'message' => 'InvalidParameters',
            'type' => 'error',
            'stamp' => '0x7f7c444253d0',
            'debug' => 'Action post parameter(s) invalid: email: invalid-email',
            'subjects' => [ 'email' ],
            'reasons' => [
                'email' => 'invalid-email',
            ],
        ],
    ];

    public const VALID_ITEM_2 = [
        'entity' => [
            'error' => 'server_error',
            'message' => 'UnknownError',
            'type' => 'error',
            'stamp' => '0x0',
        ],
    ];

    public const INVALID_ITEM_1 = [
        'entity' => [
            'error' => '',
            'message' => '',
            'type' => 'exception',
            'stamp' => 'stamp',
            'debug' => 'Action post parameter(s) invalid: email: invalid-email',
            'subjects' => [ 'email' ],
            'reasons' => [
                'email' => 'invalid-email',
            ],
        ],
        'violations' => [
            'The value of error should not be blank.',
            'The value of message should not be blank.',
        ],
    ];

    public const INVALID_ITEM_2 = [
        'entity' => [
            'error' => 'error',
            'message' => 'message',
            'type' => '',
            'stamp' => '',
        ],
        'violations' => [
            'The value of type should not be blank.',
            'The value of stamp should not be blank.',
        ],
    ];

    protected ErrorResponseEntity $sut;

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testMakeWithoutDataWillWorkAsExpected(): void
    {
        $this->sut = ErrorResponseEntity::make();
        $this->assertInstanceOf(ErrorResponseEntity::class, $this->sut);
    }

    /**
     * @return iterable<array<array<string, mixed>>>
     */
    public function getValidErrorResponseEntityData(): iterable
    {
        yield [ self::VALID_ITEM_1 ];
        yield [ self::VALID_ITEM_2 ];
    }

    /**
     * @dataProvider getValidErrorResponseEntityData
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param array<array<string, mixed>> $data
     * @throws EntityValidationException
     * @return void
     */
    public function testMakeWithDataWillSucceedAsExpected(array $data): void
    {
        $this->sut = ErrorResponseEntity::make($data['entity']);
        $this->assertSame(
            $data['entity']['error'],
            $this->sut->getError()
        );
        $this->assertSame(
            $data['entity']['message'],
            $this->sut->getMessage()
        );
        $this->assertSame(
            $data['entity']['type'],
            $this->sut->getType()
        );
        $this->assertSame(
            $data['entity']['stamp'],
            $this->sut->getStamp()
        );
        $this->assertSame(
            $data['entity']['debug'] ?? null,
            $this->sut->getDebug()
        );
        $this->assertSame(
            $data['entity']['subjects'] ?? null,
            $this->sut->getSubjects()
        );
        $this->assertSame(
            $data['entity']['reasons'] ?? null,
            $this->sut->getReasons()
        );
    }

    /**
     * @return iterable<int, array<array<string, array<int|string, mixed>>>>
     */
    public function getInvalidErrorResponseEntityData(): iterable
    {
        yield [ self::INVALID_ITEM_1 ];
        yield [ self::INVALID_ITEM_2 ];
    }

    /**
     * @dataProvider getInvalidErrorResponseEntityData
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param array<array<string, array<int|string, string>>> $data
     * @return void
     */
    public function testMakeWithDataWillFailAsExpected(array $data): void
    {
        try {
            ErrorResponseEntity::make($data['entity']);
        } catch (EntityValidationException $exception) {
            $this->assertSame($data['violations'], $exception->getViolations());
        }
    }
}
