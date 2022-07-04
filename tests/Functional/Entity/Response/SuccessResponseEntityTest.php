<?php

declare(strict_types=1);

namespace Functional\Entity\Response;

use WeGetFinancing\SDK\Entity\Response\SuccessResponseEntity;
use WeGetFinancing\SDK\Exception\EntityValidationException;
use PHPUnit\Framework\TestCase;

final class SuccessResponseEntityTest extends TestCase
{
    public const VALID_ITEM_1 = [
        'entity' => [
            'amount' => '1000.00',
            'href' => 'https://wegetfinancing.com',
            'invId' => '2da6c35d54253098f8e0099014323712',
        ],
        'expected' => [
            'amount' => '1000.00',
            'href' => 'https://wegetfinancing.com',
            'invId' => '2da6c35d54253098f8e0099014323712',
        ],
    ];

    public const VALID_ITEM_2 = [
        'entity' => [
            'amount' => '999.666',
            'href' => 'https://wegetfinancing.com',
            'inv_id' => 'd28ae0657bde808b6fce26bbbe18b690',
        ],
        'expected' => [
            'amount' => '999.66',
            'href' => 'https://wegetfinancing.com',
            'invId' => 'd28ae0657bde808b6fce26bbbe18b690',
        ],
    ];

    public const INVALID_ITEM_1 = [
        'entity' => [
            'amount' => '',
            'href' => '',
            'inv_id' => '',
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

    public const INVALID_ITEM_2 = [
        'entity' => [
            'href' => '',
            'inv_id' => '',
        ],
        'violations' => [
            7 => [
                'The value of amount is not a valid MoneyEntity.',
                'The value of href should not be blank.',
                'The value of inv id should not be blank.',
            ],
            8 => [
                'The value of amount is not a valid MoneyEntity.',
                'The value of href should not be blank.',
                'The value of inv id should not be blank.',
            ],
        ],
    ];

    public const INVALID_ITEM_3 = [
        'entity' => [
            'amount' => '999.666',
            'href' => 'invalid-url',
            'inv_id' => 'd28ae0657bde808b6fce26bbbe18b690',
        ],
        'violations' => [
            7 => [ 'The value of href url is not a valid URL.' ],
            8 => [ 'The value of href url is not a valid URL.' ],
        ],
    ];

    protected SuccessResponseEntity $sut;

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testMakeWithoutDataWillWorkAsExpected(): void
    {
        $this->sut = SuccessResponseEntity::make();
        $this->assertInstanceOf(SuccessResponseEntity::class, $this->sut);
    }

    /**
     * @return iterable<array<array<string, mixed>>>
     */
    public function getValidSuccessResponseEntityData(): iterable
    {
        yield [ self::VALID_ITEM_1 ];
        yield [ self::VALID_ITEM_2 ];
    }

    /**
     * @dataProvider getValidSuccessResponseEntityData
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param array<array<string, mixed>> $data
     * @throws EntityValidationException
     * @return void
     */
    public function testMakeWithDataWillSucceedAsExpected(array $data): void
    {
        $this->sut = SuccessResponseEntity::make($data['entity']);
        $this->assertSame(
            $data['expected']['amount'],
            $this->sut->getAmount()
        );
        $this->assertSame(
            $data['expected']['href'],
            $this->sut->getHref()
        );
        $this->assertSame(
            $data['expected']['invId'],
            $this->sut->getInvId()
        );
    }
    /**
     * @return iterable<int, array<array<string, array<int|string, mixed>>>>
     */
    public function getInvalidSuccessResponseEntityData(): iterable
    {
        yield [ self::INVALID_ITEM_1 ];
        yield [ self::INVALID_ITEM_2 ];
        yield [ self::INVALID_ITEM_3 ];
    }

    /**
     * @dataProvider getInvalidSuccessResponseEntityData
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param mixed[] $data
     * @return void
     */
    public function testMakeWithDataWillFailAsExpected(array $data): void
    {
        try {
            SuccessResponseEntity::make($data['entity']);
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
