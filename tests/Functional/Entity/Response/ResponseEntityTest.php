<?php

declare(strict_types=1);

namespace Functional\Entity\Response;

use Functional\Entity\EntityValidationErrorsMapperTrait;
use PHPUnit\Framework\TestCase;
use WeGetFinancing\SDK\Entity\Response\ResponseEntity;
use WeGetFinancing\SDK\Exception\EntityValidationException;

final class ResponseEntityTest extends TestCase
{
    use EntityValidationErrorsMapperTrait;

    public const VALID_ITEM_1 = [
        'entity' => [
            'isSuccess' => true,
            'code' => '204',
        ],
        'expected' => [
            'isSuccess' => true,
            'code' => '204',
            'data' => [],
        ],
    ];

    public const VALID_ITEM_2 = [
        'entity' => [
            'isSuccess' => false,
            'code' => '400',
            'data' => LoanErrorResponseEntityTest::VALID_ITEM_1['entity'],
        ],
        'expected' => [
            'isSuccess' => false,
            'code' => '400',
            'data' => LoanErrorResponseEntityTest::VALID_ITEM_1['entity'],
        ],
    ];

    public const VALID_ITEM_3 = [
        'entity' => [
            'isSuccess' => true,
            'code' => '201',
            'data' => LoanSuccessResponseEntityTest::VALID_ITEM_2['entity'],
        ],
        'expected' => [
            'isSuccess' => true,
            'code' => '201',
            'data' => LoanSuccessResponseEntityTest::VALID_ITEM_2['entity'],
        ],
    ];

    public const VALID_ITEM_4 = [
        'entity' => [
            'isSuccess' => false,
            'code' => '500',
            'data' => LoanErrorResponseEntityTest::VALID_ITEM_2['entity'],
        ],
        'expected' => [
            'isSuccess' => false,
            'code' => '500',
            'data' => LoanErrorResponseEntityTest::VALID_ITEM_2['entity'],
        ],
    ];

    public const INVALID_ITEM_1 = [
        'entity' => [
            'isSuccess' => false,
        ],
        'violations' => [
            7 => [
                'The value of code should not be blank.',
                'The value of code should not be null.',
            ],
            8 => [
                'The value of code should not be blank.',
                'The value of code should not be null.',
            ],
        ],
    ];

    public const INVALID_ITEM_2 = [
        'entity' => [
            'code' => '500',
        ],
        'violations' => [
            7 => [
                'The value of isSuccess should not be null.',
            ],
            8 => [
                'The value of isSuccess should not be null.',
            ],
        ],
    ];

    protected ResponseEntity $sut;

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testMakeWithoutDataWillWorkAsExpected(): void
    {
        $this->sut = ResponseEntity::make();
        $this->assertInstanceOf(ResponseEntity::class, $this->sut);
    }

    /**
     * @return iterable<array<array<string, mixed>>>
     */
    public function getValidResponseEntityData(): iterable
    {
        yield [ self::VALID_ITEM_1 ];
        yield [ self::VALID_ITEM_2 ];
        yield [ self::VALID_ITEM_3 ];
        yield [ self::VALID_ITEM_4 ];
    }

    /**
     * @dataProvider getValidResponseEntityData
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param array<array<string, mixed>> $data
     * @throws EntityValidationException
     * @return void
     */
    public function testMakeWithDataWillSucceedAsExpected(array $data): void
    {
        $this->sut = ResponseEntity::make($data['entity']);
        $this->assertSame(
            $data['expected']['isSuccess'],
            $this->sut->getIsSuccess()
        );
        $this->assertSame(
            $data['expected']['code'],
            $this->sut->getCode()
        );

        $this->assertSame($data['expected'], $this->sut->toArray());
    }

    /**
     * @return iterable<int, array<array<string, array<int|string, mixed>>>>
     */
    public function getInvalidResponseEntityData(): iterable
    {
        yield [ self::INVALID_ITEM_1 ];
        yield [ self::INVALID_ITEM_2 ];
    }

    /**
     * @dataProvider getInvalidResponseEntityData
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param mixed[] $data
     * @return void
     */
    public function testMakeWithDataWillFailAsExpected(array $data): void
    {
        try {
            ResponseEntity::make($data['entity']);
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
