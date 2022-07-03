<?php

declare(strict_types=1);

namespace Functional\Entity\Response;

use App\Entity\Response\ErrorResponseEntity;
use App\Entity\Response\LoanResponseEntity;
use App\Entity\Response\SuccessResponseEntity;
use App\Exception\EntityValidationException;
use PHPUnit\Framework\TestCase;

final class LoanResponseEntityTest extends TestCase
{
    public const VALID_ITEM_1 = [
        'entity' => [
            'isSuccess' => true,
            'code' => '200',
            'response' => SuccessResponseEntityTest::VALID_ITEM_1['entity'],
        ],
        'expected' => [
            'isSuccess' => true,
            'code' => '200',
            'success' => SuccessResponseEntityTest::VALID_ITEM_1['expected'],
        ],
    ];

    public const VALID_ITEM_2 = [
        'entity' => [
            'isSuccess' => false,
            'code' => '400',
            'response' => ErrorResponseEntityTest::VALID_ITEM_1['entity'],
        ],
        'expected' => [
            'isSuccess' => false,
            'code' => '400',
            'error' => ErrorResponseEntityTest::VALID_ITEM_1['entity'],
        ],
    ];

    public const VALID_ITEM_3 = [
        'entity' => [
            'isSuccess' => true,
            'code' => '201',
            'response' => SuccessResponseEntityTest::VALID_ITEM_2['entity'],
        ],
        'expected' => [
            'isSuccess' => true,
            'code' => '201',
            'success' => SuccessResponseEntityTest::VALID_ITEM_2['expected'],
        ],
    ];

    public const VALID_ITEM_4 = [
        'entity' => [
            'isSuccess' => false,
            'code' => '404',
            'response' => ErrorResponseEntityTest::VALID_ITEM_2['entity'],
        ],
        'expected' => [
            'isSuccess' => false,
            'code' => '404',
            'error' => ErrorResponseEntityTest::VALID_ITEM_2['entity'],
        ],
    ];

    public const INVALID_ITEM_1 = [
        'entity' => [
            'code' => '',
            'response' => ErrorResponseEntityTest::VALID_ITEM_2['entity'],
        ],
        'violations' => [
            7 => [
                'The value of isSuccess should not be null.',
                'The value of code should not be blank.',
            ],
            8 => [
                'The value of isSuccess should not be null.',
                'The value of code should not be blank.',
            ],
        ],
    ];

    public const INVALID_ITEM_2 = [
        'entity' => [
            'response' => ErrorResponseEntityTest::VALID_ITEM_2['entity'],
        ],
        'violations' => [
            7 => [
                'The value of isSuccess should not be null.',
                'The value of code should not be blank.',
                'The value of code should not be null.',
            ],
            8 => [
                'The value of isSuccess should not be null.',
                'The value of code should not be blank.',
                'The value of code should not be null.',
            ],
        ],
    ];

    public const INVALID_ITEM_3 = [
        'entity' => [
            'isSuccess' => false,
            'code' => '404',
            'response' => ErrorResponseEntityTest::INVALID_ITEM_1['entity'],
        ],
        'violations' => [
            7 => [
                'The value of error should not be blank.',
                'The value of message should not be blank.',
            ],
            8 => [
                'The value of error should not be blank.',
                'The value of message should not be blank.',
            ],
        ],
    ];

    public const INVALID_ITEM_4 = [
        'entity' => [
            'isSuccess' => true,
            'code' => '200',
            'response' => SuccessResponseEntityTest::INVALID_ITEM_1['entity'],
        ],
        'violations' => [
            7 => [
                'Amount value is not a valid numeric.',
                'Amount value should not be blank.',
            ],
            8 => [
                'Amount value is not a valid numeric.',
                'Amount value should be either positive or zero if allowed.',
                'Amount value should not be blank.',
            ],
        ],
    ];

    public const INVALID_ITEM_5 = [
        'entity' => [
            'isSuccess' => false,
            'code' => '200',
        ],
        'violations' => [
            7 => [ 'The loan response entity needs a valid response array to be initialised.' ],
            8 => [ 'The loan response entity needs a valid response array to be initialised.' ],
        ],
    ];

    protected LoanResponseEntity $sut;

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testMakeWithoutDataWillWorkAsExpected(): void
    {
        $this->sut = LoanResponseEntity::make();
        $this->assertInstanceOf(LoanResponseEntity::class, $this->sut);
    }

    /**
     * @return iterable<array<array<string, mixed>>>
     */
    public function getValidLoanResponseEntityData(): iterable
    {
        yield [ self::VALID_ITEM_1 ];
        yield [ self::VALID_ITEM_2 ];
        yield [ self::VALID_ITEM_3 ];
        yield [ self::VALID_ITEM_4 ];
    }

    /**
     * @dataProvider getValidLoanResponseEntityData
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param array<array<string, mixed>> $data
     * @throws EntityValidationException
     * @return void
     */
    public function testMakeWithDataWillSucceedAsExpected(array $data): void
    {
        $this->sut = LoanResponseEntity::make($data['entity']);
        $this->assertSame(
            $data['expected']['isSuccess'],
            $this->sut->getIsSuccess()
        );
        $this->assertSame(
            $data['expected']['code'],
            $this->sut->getCode()
        );

        if (true === $this->sut->getIsSuccess()) {
            /** @var SuccessResponseEntity $success */
            $success = $this->sut->getSuccess();
            $this->assertSame(
                $data['expected']['success']['amount'],
                $success->getAmount()
            );
            $this->assertSame(
                $data['expected']['success']['href'],
                $success->getHref()
            );
            $this->assertSame(
                $data['expected']['success']['invId'],
                $success->getInvId()
            );
            return;
        }

        /** @var ErrorResponseEntity $error */
        $error = $this->sut->getError();
        $this->assertSame(
            $data['expected']['error']['error'],
            $error->getError()
        );
        $this->assertSame(
            $data['expected']['error']['message'],
            $error->getMessage()
        );
        $this->assertSame(
            $data['expected']['error']['type'],
            $error->getType()
        );
        $this->assertSame(
            $data['expected']['error']['stamp'],
            $error->getStamp()
        );
        $this->assertSame(
            $data['expected']['error']['debug'] ?? null,
            $error->getDebug()
        );
        $this->assertSame(
            $data['expected']['error']['subjects'] ?? null,
            $error->getSubjects()
        );
        $this->assertSame(
            $data['expected']['error']['reasons'] ?? null,
            $error->getReasons()
        );
    }

    /**
     * @return iterable<int, array<array<string, array<int|string, mixed>>>>
     */
    public function getInvalidLoanResponseEntityData(): iterable
    {
        yield [ self::INVALID_ITEM_1 ];
        yield [ self::INVALID_ITEM_2 ];
        yield [ self::INVALID_ITEM_3 ];
        yield [ self::INVALID_ITEM_4 ];
        yield [ self::INVALID_ITEM_5 ];
    }

    /**
     * @dataProvider getInvalidLoanResponseEntityData
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param mixed[] $data
     * @return void
     */
    public function testMakeWithDataWillFailAsExpected(array $data): void
    {
        try {
            LoanResponseEntity::make($data['entity']);
        } catch (EntityValidationException $exception) {
            $this->assertSame(
                (version_compare(PHP_VERSION, '8.0.0', '<'))
                    ? $data['violations'][7]
                    : $data['violations'][8],
                $exception->getViolations()
            );
        }
    }
}
