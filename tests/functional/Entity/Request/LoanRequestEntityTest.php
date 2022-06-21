<?php

declare(strict_types=1);

namespace functional\Entity\Request;

use App\Entity\Request\LoanRequestEntity;
use App\Exception\EntityValidationException;
use PHPUnit\Framework\TestCase;

/**
 * @functional
 */
final class LoanRequestEntityTest extends TestCase
{
    public const VALID_ITEM_1 = [
        'entity' => [
            'firstName' => 'John',
            'last_name' => 'Doe',
            'shippingAmount' => 15.2,
            'version' => '1.9',
            'email' => 'john.doe@example.com',
            'phone' => '2223456789',
            'merchant_transaction_id' => '66699',
            'success_url' => 'https://wegetfinancing.com/successurl',
            'failure_url' => 'https://wegetfinancing.com/failureurl',
            'postback_url' => 'https://wegetfinancing.com/postbackurl',
            'billing_address' => AddressEntityTest::VALID_ITEM_1['entity'],
            'shipping_address' => AddressEntityTest::VALID_ITEM_1['entity'],
            'cartItems' => [
                CartItemEntityTest::VALID_ITEM_1['entity'],
                CartItemEntityTest::VALID_ITEM_2['entity'],
                CartItemEntityTest::VALID_ITEM_3['entity'],
            ],
        ],
        'expected' => [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'shipping_amount' => '15.20',
            'version' => '1.9',
            'email' => 'john.doe@example.com',
            'phone' => '2223456789',
            'merchant_transaction_id' => '66699',
            'success_url' => 'https://wegetfinancing.com/successurl',
            'failure_url' => 'https://wegetfinancing.com/failureurl',
            'postback_url' => 'https://wegetfinancing.com/postbackurl',
            'billing_address' => AddressEntityTest::VALID_ITEM_1['expected'],
            'shipping_address' => AddressEntityTest::VALID_ITEM_1['expected'],
            'cart_items' => [
                CartItemEntityTest::VALID_ITEM_1['expected'],
                CartItemEntityTest::VALID_ITEM_2['expected'],
                CartItemEntityTest::VALID_ITEM_3['expected'],
            ],
        ],
    ];

    public const VALID_ITEM_2 = [
        'entity' => [
            'firstName' => 'Riccardo',
            'last_name' => 'De Leo',
            'shippingAmount' => 1900.210004,
            'version' => '1.9',
            'email' => 'riccardo.deleo@example.com',
            'phone' => '2223456789',
            'merchant_transaction_id' => '12345',
            'success_url' => 'https://wegetfinancing.com/successurl',
            'failure_url' => 'https://wegetfinancing.com/failureurl',
            'postback_url' => 'https://wegetfinancing.com/postbackurl',
            'billing_address' => AddressEntityTest::VALID_ITEM_2['entity'],
            'shipping_address' => AddressEntityTest::VALID_ITEM_2['entity'],
            'cartItems' => [
                CartItemEntityTest::VALID_ITEM_1['entity'],
                CartItemEntityTest::VALID_ITEM_3['entity'],
            ],
        ],
        'expected' => [
            'first_name' => 'Riccardo',
            'last_name' => 'De Leo',
            'shipping_amount' => '1900.21',
            'version' => '1.9',
            'email' => 'riccardo.deleo@example.com',
            'phone' => '2223456789',
            'merchant_transaction_id' => '12345',
            'success_url' => 'https://wegetfinancing.com/successurl',
            'failure_url' => 'https://wegetfinancing.com/failureurl',
            'postback_url' => 'https://wegetfinancing.com/postbackurl',
            'billing_address' => AddressEntityTest::VALID_ITEM_2['expected'],
            'shipping_address' => AddressEntityTest::VALID_ITEM_2['expected'],
            'cart_items' => [
                CartItemEntityTest::VALID_ITEM_1['expected'],
                CartItemEntityTest::VALID_ITEM_3['expected'],
            ],
        ],
    ];

    public const INVALID_ITEM_1 = [
        'entity' => [
            'firstName' => 'J',
            'last_name' => 'D',
            'shippingAmount' => 15.2,
            'version' => '1.9',
            'email' => 'john.doe@example.com',
            'phone' => '01234567890',
            'merchant_transaction_id' => '66699',
            'success_url' => 'https://wegetfinancing.com/successurl',
            'failure_url' => 'https://wegetfinancing.com/failureurl',
            'postback_url' => 'https://wegetfinancing.com/postbackurl',
            'billing_address' => AddressEntityTest::VALID_ITEM_1['entity'],
            'shipping_address' => AddressEntityTest::VALID_ITEM_1['entity'],
            'cartItems' => [
                CartItemEntityTest::VALID_ITEM_1['entity'],
                CartItemEntityTest::VALID_ITEM_2['entity'],
                CartItemEntityTest::VALID_ITEM_3['entity'],
            ],
        ],
        'violations' => [
            'The value of first name is too short. It should have 2 characters or more.',
            'The value of last name is too short. It should have 2 characters or more.',
            'The value of phone have to be 10 digits only.',
        ],
    ];

    public const INVALID_ITEM_2 = [
        'entity' => [
            'firstName' => 'J',
            'last_name' => 'D',
            'shippingAmount' => 15.2,
            'version' => '1.9',
            'email' => 'john.doe@example.com',
            'phone' => '01234567890',
            'merchant_transaction_id' => '66699',
            'success_url' => 'https://wegetfinancing.com/successurl',
            'failure_url' => 'https://wegetfinancing.com/failureurl',
            'postback_url' => 'https://wegetfinancing.com/postbackurl',
            'billing_address' => AddressEntityTest::VALID_ITEM_1['entity'],
            'shipping_address' => AddressEntityTest::VALID_ITEM_1['entity'],
            'cartItems' => [
                CartItemEntityTest::INVALID_ITEM_4['entity'],
                CartItemEntityTest::VALID_ITEM_2['entity'],
                CartItemEntityTest::VALID_ITEM_3['entity'],
            ],
        ],
        'violations' => [
            'Unit Tax Cannot assign null to property App\Entity\MoneyEntity::$value of type string',
        ],
    ];

    public const INVALID_ITEM_3 = [
        'entity' => [
            'firstName' => 'J',
            'last_name' => 'D',
            'shippingAmount' => 15.2,
            'version' => '1.9',
            'email' => 'john.doe@example.com',
            'phone' => '01234567890',
            'merchant_transaction_id' => '66699',
            'success_url' => 'https://wegetfinancing.com/successurl',
            'failure_url' => 'https://wegetfinancing.com/failureurl',
            'postback_url' => 'https://wegetfinancing.com/postbackurl',
            'billing_address' => AddressEntityTest::INVALID_ITEM_2['entity'],
            'shipping_address' => AddressEntityTest::VALID_ITEM_1['entity'],
            'cartItems' => [
                CartItemEntityTest::INVALID_ITEM_4['entity'],
                CartItemEntityTest::VALID_ITEM_2['entity'],
                CartItemEntityTest::VALID_ITEM_3['entity'],
            ],
        ],
        'violations' => [
            'The value of state should have exactly 2 characters.',
            'The value of zipcode should contain only 5 numbers optionally followed by a dash and 4 numbers.',
        ],
    ];

    public const INVALID_ITEM_4 = [
        'entity' => [
            'firstName' => null,
            'last_name' => 'D',
            'shippingAmount' => 15.2,
            'version' => '1.9',
            'email' => 15,
            'phone' => '01234567890',
            'merchant_transaction_id' => '66699',
            'success_url' => 'https://wegetfinancing.com/successurl',
            'failure_url' => 'https://wegetfinancing.com/failureurl',
            'postback_url' => 'https://wegetfinancing.com/postbackurl',
            'billing_address' => AddressEntityTest::INVALID_ITEM_2['entity'],
            'shipping_address' => AddressEntityTest::VALID_ITEM_1['entity'],
            'cartItems' => [
                CartItemEntityTest::INVALID_ITEM_4['entity'],
                CartItemEntityTest::VALID_ITEM_2['entity'],
                CartItemEntityTest::VALID_ITEM_3['entity'],
            ],
        ],
        'violations' => [
            'Cannot assign null to property App\Entity\Request\LoanRequestEntity::$firstName of type string',
        ],
    ];

    protected LoanRequestEntity $sut;

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testMakeWithoutDataWillWorkAsExpected(): void
    {
        $this->sut = LoanRequestEntity::make();
        $this->assertInstanceOf(LoanRequestEntity::class, $this->sut);
    }

    /**
     * @return iterable<array<array<string, array<string, mixed>>>>
     */
    public function getValidLoanRequestEntityData(): iterable
    {
        yield [ self::VALID_ITEM_1 ];
        yield [ self::VALID_ITEM_2 ];
    }

    /**
     * @dataProvider getValidLoanRequestEntityData
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param array<array<string, array<int|string, string>>> $data
     * @throws EntityValidationException
     * @return void
     */
    public function testMakeWithDataWillSucceedAsExpected(array $data): void
    {
        $this->sut = LoanRequestEntity::make($data['entity']);
        $request = $this->sut->getWeGetFinancingRequest();

        foreach ($request as $field => $value) {
            $this->assertSame($data['expected'][$field], $value);
        }
    }

    /**
     * @return iterable<int, array<array<string, array<int|string, mixed>>>>
     */
    public function getInvalidLoanRequestEntityData(): iterable
    {
        yield [ self::INVALID_ITEM_1 ];
        yield [ self::INVALID_ITEM_2 ];
        yield [ self::INVALID_ITEM_3 ];
        yield [ self::INVALID_ITEM_4 ];
    }

    /**
     * @dataProvider getInvalidLoanRequestEntityData
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param array<array<string, array<int|string, string>>> $data
     * @return void
     */
    public function testMakeWithDataWillFailAsExpected(array $data): void
    {
        try {
            LoanRequestEntity::make($data['entity']);
        } catch (EntityValidationException $exception) {
            $this->assertSame($data['violations'], $exception->getViolations());
        }
    }
}
