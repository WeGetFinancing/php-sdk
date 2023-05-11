<?php

declare(strict_types=1);

namespace Integration;

use Functional\Entity\Request\UpdateShippingStatusRequestEntityTest;
use WeGetFinancing\SDK\Client;
use WeGetFinancing\SDK\Entity\AuthEntity;
use WeGetFinancing\SDK\Entity\Request\LoanRequestEntity;
use WeGetFinancing\SDK\Entity\Request\UpdateShippingStatusRequestEntity;
use Functional\Entity\Request\LoanRequestEntityTest;
use PHPUnit\Framework\TestCase;
use WeGetFinancing\SDK\Service\PpeClient;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
final class ClientTest extends TestCase
{
    private Client $sut;

    public function setUp(): void
    {
        $username = getenv('TEST_USERNAME');
        $password = getenv('TEST_PASSWORD');
        $merchantId = getenv('TEST_MERCHANT_ID');

        $this->assertIsNotBool($username);
        $this->assertIsNotBool($password);
        $this->assertIsNotBool($merchantId);

        $auth = AuthEntity::make([
            'username' => $username,
            'password' => $password,
            'merchantId' => $merchantId,
        ]);

        $this->sut = new Client($auth);
    }

    public function testSuccessfullyRequestNewLoanCall(): void
    {
        $loanRequest = LoanRequestEntity::make(LoanRequestEntityTest::VALID_ITEM_1['entity']);
        $response = $this->sut->requestNewLoan($loanRequest);

        $this->assertTrue($response->getIsSuccess());
        $this->assertSame('200', $response->getCode());

        $data = $response->getData();
        $this->assertArrayHasKey('invId', $data);
        $this->assertArrayHasKey('href', $data);
        $this->assertArrayHasKey('amount', $data);
        $this->assertSame('1640.94', $data['amount']);
    }

    public function testUnsuccessfullyV3Auth(): void
    {
        $username = getenv('TEST_USERNAME');
        $password = 'wrong_password';
        $merchantId = getenv('TEST_MERCHANT_ID');

        $this->assertIsNotBool($username);
        $this->assertIsNotBool($password);
        $this->assertIsNotBool($merchantId);

        $auth = AuthEntity::make([
            'username' => $username,
            'password' => $password,
            'merchantId' => $merchantId,
        ]);

        $this->sut = new Client($auth);

        $updateRequest = UpdateShippingStatusRequestEntity::make(
            UpdateShippingStatusRequestEntityTest::VALID_ITEM_1['entity']
        );

        $response = $this->sut->updateStatus($updateRequest);
        $data = $response->getData();

        $this->assertFalse($response->getIsSuccess());
        $this->assertArrayHasKey('created_at', $data);
        $this->assertArrayHasKey('error_type', $data);
        $this->assertArrayHasKey('messages', $data);
        $this->assertSame('AuthenticationError', $data['error_type']);
        $this->assertArrayHasKey('Invalid or missing credentials', $data['messages'][0]);
    }

    public function testSuccessfullyUpdateShippingStatus(): void
    {
        $loanRequest = LoanRequestEntity::make(LoanRequestEntityTest::VALID_ITEM_1['entity']);
        $loanResponse = $this->sut->requestNewLoan($loanRequest);
        $this->assertTrue($loanResponse->getIsSuccess());

        $this->assertArrayHasKey('invId', $loanResponse->getData());

        $data = UpdateShippingStatusRequestEntityTest::VALID_ITEM_1['entity'];
        $data['invId'] = $loanResponse->getData()['invId'];

        $updateRequest = UpdateShippingStatusRequestEntity::make($data);

        $response = $this->sut->updateStatus($updateRequest);

        $this->assertTrue($response->getIsSuccess());

        $this->assertEquals(204, $response->getCode());
    }

    public function testEmptyPpe(): void
    {
        $response = $this->sut->testPpe((string)getenv('MERCHANT_TOKEN_EMPTY'));
        $this->assertEquals(PpeClient::TEST_EMPTY_RESPONSE, $response['status']);
        $this->assertEquals(PpeClient::EMPTY_LENDERS_MESSAGE, $response['message']);
    }

    public function testErrorPpe(): void
    {
        $response = $this->sut->testPpe((string)getenv('MERCHANT_TOKEN_ERROR'));
        $this->assertEquals(PpeClient::TEST_ERROR_RESPONSE, $response['status']);
    }

    public function testSuccessPpe(): void
    {
        $response = $this->sut->testPpe((string)getenv('MERCHANT_TOKEN_SUCCESS'));
        $this->assertEquals(PpeClient::TEST_SUCCESS_RESPONSE, $response['status']);
    }
}
