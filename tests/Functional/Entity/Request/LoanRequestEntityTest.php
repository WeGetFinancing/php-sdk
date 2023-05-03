<?php

declare(strict_types=1);

namespace Functional\Entity\Request;

use Functional\Entity\EntityValidationErrorsMapperTrait;
use PHPUnit\Framework\TestCase;
use WeGetFinancing\SDK\Entity\Request\LoanRequestEntity;
use WeGetFinancing\SDK\Exception\EntityValidationException;

/**
 * @functional
 */
final class LoanRequestEntityTest extends TestCase
{
    use EntityValidationErrorsMapperTrait;

    public const VALID_ITEM_1 = [
        'entity' => [
            'firstName' => 'Criscel',
            'last_name' => 'Doe',
            'shippingAmount' => 15.2,
            'version' => '1.9',
            'email' => 'mary.doe@example.com',
            'phone' => '2223456789',
            'merchant_transaction_id' => '66699',
            'success_url' => '',
            'failure_url' => 'https://wegetfinancing.com/failureurl',
            'postback_url' => 'https://wegetfinancing.com/postbackurl',
            'softwareName' => 'Magento',
            'softwareVersion' => '2.4.5',
            'softwarePluginVersion' => '1.3',
            'billing_address' => AddressEntityTest::VALID_ITEM_1['entity'],
            'shipping_address' => AddressEntityTest::VALID_ITEM_1['entity'],
            'cartItems' => [
                CartItemEntityTest::VALID_ITEM_1['entity'],
                CartItemEntityTest::VALID_ITEM_2['entity'],
                CartItemEntityTest::VALID_ITEM_3['entity'],
            ],
        ],
        'expected' => [
            'first_name' => 'Criscel',
            'last_name' => 'Doe',
            'shipping_amount' => '15.20',
            'version' => '1.9',
            'email' => 'mary.doe@example.com',
            'phone' => '2223456789',
            'merchant_transaction_id' => '66699',
            'success_url' => '',
            'failure_url' => 'https://wegetfinancing.com/failureurl',
            'postback_url' => 'https://wegetfinancing.com/postbackurl',
            'software_name' => 'Magento',
            'software_version' => '2.4.5',
            'software_plugin_version' => '1.3',
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
            'software_name' => 'WordPress',
            'software_version' => '6',
            'software_plugin_version' => '1',
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
            'software_name' => 'WordPress',
            'software_version' => '6',
            'software_plugin_version' => '1',
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
            'email' => 'invalid-email',
            'phone' => '01234567890',
            'merchant_transaction_id' => '66699',
            'success_url' => 'invalid-url',
            'failure_url' => 'invalid-url',
            'postback_url' => 'invalid-url',
            'software_name' => null,
            'software_version' => null,
            'software_plugin_version' => null,
            'billing_address' => AddressEntityTest::VALID_ITEM_1['entity'],
            'shipping_address' => AddressEntityTest::VALID_ITEM_1['entity'],
            'cartItems' => [
                CartItemEntityTest::VALID_ITEM_1['entity'],
                CartItemEntityTest::VALID_ITEM_2['entity'],
                CartItemEntityTest::VALID_ITEM_3['entity'],
            ],
        ],
        'violations' => [
            7 => [
                'The value of first name is too short. It should have 2 characters or more.',
                'The value of last name is too short. It should have 2 characters or more.',
                'The value of email is not a valid email address.',
                'The value of phone have to be 10 digits only.',
                'The value of success url is not a valid URL.',
                'The value of failure url is not a valid URL.',
                'The value of postback url is not a valid URL.',
                'The value of software name should not be blank.',
                'The value of software version should not be blank.',
                'The value of software plugin version should not be blank.',
            ],
            8 => [
                'The value of first name is too short. It should have 2 characters or more.',
                'The value of last name is too short. It should have 2 characters or more.',
                'The value of email is not a valid email address.',
                'The value of phone have to be 10 digits only.',
                'The value of success url is not a valid URL.',
                'The value of failure url is not a valid URL.',
                'The value of postback url is not a valid URL.',
                'The value of software name should not be blank.',
                'The value of software version should not be blank.',
                'The value of software plugin version should not be blank.',
            ],
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
            'software_name' => 'WordPress',
            'software_version' => '6',
            'software_plugin_version' => '1',
            'billing_address' => AddressEntityTest::VALID_ITEM_1['entity'],
            'shipping_address' => AddressEntityTest::VALID_ITEM_1['entity'],
            'cartItems' => [
                CartItemEntityTest::INVALID_ITEM_4['entity'],
                CartItemEntityTest::VALID_ITEM_2['entity'],
                CartItemEntityTest::VALID_ITEM_3['entity'],
            ],
        ],
        'violations' => [
            7 => [ 'Unit Tax Typed property WeGetFinancing\SDK\Entity\MoneyEntity::$value must be string, null used' ],
            8 => [ 'Unit Tax Cannot assign null to property WeGetFinancing\SDK\Entity\MoneyEntity::$value of type string' ],
        ],
    ];

    public const INVALID_ITEM_3 = [
        'entity' => [
            'firstName' => 'J',
            'last_name' => 'D',
            'shippingAmount' => 15.2,
            'version' => '1.9',
            'email' => 'invalid-email',
            'phone' => '01234567890',
            'merchant_transaction_id' => '66699',
            'success_url' => 'invalid-url',
            'failure_url' => 'invalid-url',
            'postback_url' => 'invalid-url',
            'billing_address' => AddressEntityTest::INVALID_ITEM_2['entity'],
            'shipping_address' => AddressEntityTest::VALID_ITEM_1['entity'],
            'cartItems' => [
                CartItemEntityTest::INVALID_ITEM_4['entity'],
                CartItemEntityTest::VALID_ITEM_2['entity'],
                CartItemEntityTest::VALID_ITEM_3['entity'],
            ],
        ],
        'violations' => [
            7 => [
                'The value of state should have exactly 2 characters.',
                'The value of zipcode should contain only 5 numbers optionally followed by a dash and 4 numbers.',
            ],
            8 => [
                'The value of state should have exactly 2 characters.',
                'The value of zipcode should contain only 5 numbers optionally followed by a dash and 4 numbers.',
            ],
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
            'software_name' => 'Magento',
            'software_version' => '2.4.5',
            'software_plugin_version' => '1.3',
            'billing_address' => AddressEntityTest::INVALID_ITEM_2['entity'],
            'shipping_address' => AddressEntityTest::VALID_ITEM_1['entity'],
            'cartItems' => [
                CartItemEntityTest::INVALID_ITEM_4['entity'],
                CartItemEntityTest::VALID_ITEM_2['entity'],
                CartItemEntityTest::VALID_ITEM_3['entity'],
            ],
        ],
        'violations' => [
            7 => [ 'Typed property WeGetFinancing\SDK\Entity\Request\LoanRequestEntity::$firstName must be string, null used' ],
            8 => [ 'Cannot assign null to property WeGetFinancing\SDK\Entity\Request\LoanRequestEntity::$firstName of type string' ],
        ],
    ];

    public const INVALID_ITEM_5 = [
        'entity' => [
            'firstName' => '',
            'last_name' => '',
            'shippingAmount' => 15.2,
            'version' => '',
            'email' => '',
            'phone' => '',
            'merchant_transaction_id' => '',
            'success_url' => '',
            'failure_url' => '',
            'postback_url' => '',
            'billing_address' => AddressEntityTest::VALID_ITEM_1['entity'],
            'shipping_address' => AddressEntityTest::VALID_ITEM_1['entity'],
            'software_name' => 'Magento',
            'software_version' => '2.4.5',
            'software_plugin_version' => '1.3',
            'cartItems' => [
                CartItemEntityTest::VALID_ITEM_1['entity'],
                CartItemEntityTest::VALID_ITEM_2['entity'],
                CartItemEntityTest::VALID_ITEM_3['entity'],
            ],
        ],
        'violations' => [
            7 => [
                'The value of first name is too short. It should have 2 characters or more.',
                'The value of first name should not be blank.',
                'The value of last name is too short. It should have 2 characters or more.',
                'The value of last name should not be blank.',
                'The value of email should not be blank.',
                'The value of version should not be blank.',
                'The value of merchant transaction id should not be blank.',
            ],
            8 => [
                'The value of first name is too short. It should have 2 characters or more.',
                'The value of first name should not be blank.',
                'The value of last name is too short. It should have 2 characters or more.',
                'The value of last name should not be blank.',
                'The value of email should not be blank.',
                'The value of version should not be blank.',
                'The value of merchant transaction id should not be blank.',
            ],
        ],
    ];

    public const INVALID_ITEM_6 = [
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
            'software_name' => '',
            'software_version' => '',
            'software_plugin_version' => '',
            'billing_address' => AddressEntityTest::VALID_ITEM_2['entity'],
            'shipping_address' => AddressEntityTest::VALID_ITEM_2['entity'],
            'cartItems' => [
                CartItemEntityTest::VALID_ITEM_1['entity'],
                CartItemEntityTest::VALID_ITEM_3['entity'],
            ],
        ],
        'violations' => [
            7 => [
                'The value of software name should not be blank.',
                'The value of software version should not be blank.',
                'The value of software plugin version should not be blank.',
            ],
            8 => [
                'The value of software name should not be blank.',
                'The value of software version should not be blank.',
                'The value of software plugin version should not be blank.',
            ],
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
        yield [ self::INVALID_ITEM_5 ];
        yield [ self::INVALID_ITEM_6 ];
    }

    /**
     * @dataProvider getInvalidLoanRequestEntityData
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param mixed[] $data
     * @return void
     */
    public function testMakeWithDataWillFailAsExpected(array $data): void
    {
        try {
            LoanRequestEntity::make($data['entity']);
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
