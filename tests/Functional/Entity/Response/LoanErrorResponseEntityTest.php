<?php

declare(strict_types=1);

namespace Functional\Entity\Response;

use Functional\Entity\EntityValidationErrorsMapperTrait;
use PHPUnit\Framework\TestCase;
use WeGetFinancing\SDK\Entity\Response\LoanErrorResponseEntity;
use WeGetFinancing\SDK\Exception\EntityValidationException;
use WeGetFinancing\SDK\Service\Http\V1\HttpClientV1;

final class LoanErrorResponseEntityTest extends TestCase
{
    use EntityValidationErrorsMapperTrait;

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
        'expected' => [
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
            'error' => HttpClientV1::DEFAULT_ERROR_ERROR,
            'message' => HttpClientV1::DEFAULT_ERROR_MESSAGE,
            'type' => HttpClientV1::DEFAULT_ERROR_TYPE,
            'stamp' => HttpClientV1::DEFAULT_ERROR_STAMP,
        ],
        'expected' => [
            'error' => HttpClientV1::DEFAULT_ERROR_ERROR,
            'message' => HttpClientV1::DEFAULT_ERROR_MESSAGE,
            'type' => HttpClientV1::DEFAULT_ERROR_TYPE,
            'stamp' => HttpClientV1::DEFAULT_ERROR_STAMP,
            'debug' => null,
            'subjects' => null,
            'reasons' => null,
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

    protected LoanErrorResponseEntity $sut;

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testMakeWithoutDataWillWorkAsExpected(): void
    {
        $this->sut = LoanErrorResponseEntity::make();
        $this->assertInstanceOf(LoanErrorResponseEntity::class, $this->sut);
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
        $this->sut = LoanErrorResponseEntity::make($data['entity']);
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

        $this->assertSame($data['expected'], $this->sut->toArray());
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
            LoanErrorResponseEntity::make($data['entity']);
        } catch (EntityValidationException $exception) {
            $violations = $this->getViolationMessages($exception);
            $this->assertSame($data['violations'], $violations);
        }
    }
}
