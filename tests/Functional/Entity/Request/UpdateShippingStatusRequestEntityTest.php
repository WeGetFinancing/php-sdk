<?php

declare(strict_types=1);

namespace Functional\Entity\Request;

use Functional\Entity\EntityValidationErrorsMapperTrait;
use PHPUnit\Framework\TestCase;
use WeGetFinancing\SDK\Entity\Request\UpdateShippingStatusRequestEntity;
use WeGetFinancing\SDK\Exception\EntityValidationException;

class UpdateShippingStatusRequestEntityTest extends TestCase
{
    use EntityValidationErrorsMapperTrait;

    public const VALID_ITEM_1 = [
        'entity' => [
            'shippingStatus' => UpdateShippingStatusRequestEntity::STATUS_SHIPPED,
            'trackingId' => '1234',
            'trackingCompany' => 'WeGetFinancing',
            'deliveryDate' => '2023-03-03',
            'invId' => '1',
        ],
        'expected' => [
            'shipping_status' => UpdateShippingStatusRequestEntity::STATUS_SHIPPED,
            'tracking_id' => '1234',
            'tracking_company' => 'WeGetFinancing',
            'delivery_date' => '2023-03-03',
        ],
    ];

    public const VALID_ITEM_2 = [
        'entity' => [
            'shippingStatus' => UpdateShippingStatusRequestEntity::STATUS_SHORTAGE,
            'trackingId' => 'testsomehowlongtexttrackingid',
            'trackingCompany' => 'Test company',
            'deliveryDate' => '2022-12-31',
            'invId' => '2',
        ],
        'expected' => [
            'shipping_status' => UpdateShippingStatusRequestEntity::STATUS_SHORTAGE,
            'tracking_id' => 'testsomehowlongtexttrackingid',
            'tracking_company' => 'Test company',
            'delivery_date' => '2022-12-31',
        ],
    ];

    public const VALID_ITEM_3 = [
        'entity' => [
            'shippingStatus' => UpdateShippingStatusRequestEntity::STATUS_DELIVERED,
            'trackingId' => 'track1234',
            'trackingCompany' => 'another company',
            'deliveryDate' => '2022-02-10',
            'invId' => '3',
        ],
        'expected' => [
            'shipping_status' => UpdateShippingStatusRequestEntity::STATUS_DELIVERED,
            'tracking_id' => 'track1234',
            'tracking_company' => 'another company',
            'delivery_date' => '2022-02-10',
        ],
    ];

    public const INVALID_ITEM_1 = [
        'entity' => [
            'shippingStatus' => null,
            'trackingId' => null,
            'trackingCompany' => null,
            'deliveryDate' => null,
            'invId' => null,
        ],
        'violations' => [
            7 => [
                'The value of shipment status should not be null.',
                'The value of tracking id status should not be null.',
                'The value of tracking company should not be null.',
                'The value of delivery date should not be null.',
                'The value of invId should not be null.',
            ],
            8 => [
                'The value of shipment status should not be null.',
                'The value of tracking id status should not be null.',
                'The value of tracking company should not be null.',
                'The value of delivery date should not be null.',
                'The value of invId should not be null.',
            ],
        ],
    ];

    public const INVALID_ITEM_2 = [
        'entity' => [
            'shippingStatus' => 'invalid',
            'trackingId' => null,
            'trackingCompany' => null,
            'deliveryDate' => null,
            'invId' => 'valid',
        ],
        'violations' => [
            7 => [
                'Choose a valid shipment status.',
                'The value of tracking id status should not be null.',
                'The value of tracking company should not be null.',
                'The value of delivery date should not be null.',
            ],
            8 => [
                'Choose a valid shipment status.',
                'The value of tracking id status should not be null.',
                'The value of tracking company should not be null.',
                'The value of delivery date should not be null.',
            ],
        ],
    ];

    public const INVALID_ITEM_3 = [
        'entity' => [
            'shippingStatus' => 'incorrect',
            'trackingId' => 'testtrackingid',
            'trackingCompany' => 'test company',
            'deliveryDate' => 'invalid',
        ],
        'violations' => [
            7 => [
                'Choose a valid shipment status.',
                'The value of delivery date is not a valid Date with format YYYY-MM-DD.',
                'The value of invId should not be null.',
            ],
            8 => [
                'Choose a valid shipment status.',
                'The value of delivery date is not a valid Date with format YYYY-MM-DD.',
                'The value of invId should not be null.',
            ],
        ],
    ];

    public const INVALID_ITEM_4 = [
        'entity' => [
            'shippingStatus' => UpdateShippingStatusRequestEntity::STATUS_SHORTAGE,
            'trackingId' => 'testtrackingid',
            'trackingCompany' => 'test company',
            'deliveryDate' => 'invalid',
        ],
        'violations' => [
            7 => [
                'Choose a valid shipment status.',
            ],
            8 => [
                'Choose a valid shipment status.',
            ],
        ],
    ];

    protected UpdateShippingStatusRequestEntity $sut;

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testMakeWithoutDataWillWorkAsExpected(): void
    {
        $this->sut = UpdateShippingStatusRequestEntity::make();
        $this->assertInstanceOf(UpdateShippingStatusRequestEntity::class, $this->sut);
    }

    /**
     * @return iterable<array<array<string, array<string, mixed>>>>
     */
    public function getValidUpdateShippingStatusRequestEntityData(): iterable
    {
        yield [ self::VALID_ITEM_1 ];
        yield [ self::VALID_ITEM_2 ];
        yield [ self::VALID_ITEM_3 ];
    }

    /**
     * @dataProvider getValidUpdateShippingStatusRequestEntityData
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param array<array<string, array<int|string, string>>> $data
     * @throws EntityValidationException
     * @return void
     */
    public function testMakeWithDataWillSucceedAsExpected(array $data): void
    {
        $this->sut = UpdateShippingStatusRequestEntity::make($data['entity']);
        $request = $this->sut->getWeGetFinancingRequest();

        foreach ($request as $field => $value) {
            $this->assertSame($data['expected'][$field], $value);
        }
    }

    /**
     * @return iterable<int, array<array<string, array<int|string, mixed>>>>
     */
    public function getInvalidUpdateShippingStatusRequestEntityData(): iterable
    {
        yield [ self::INVALID_ITEM_1 ];
        yield [ self::INVALID_ITEM_2 ];
        yield [ self::INVALID_ITEM_3 ];
//        yield [ self::INVALID_ITEM_4 ];
    }

    /**
     * @dataProvider getInvalidUpdateShippingStatusRequestEntityData
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param mixed[] $data
     * @return void
     */
    public function testMakeWithDataWillFailAsExpected(array $data): void
    {
        try {
            UpdateShippingStatusRequestEntity::make($data['entity']);
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
