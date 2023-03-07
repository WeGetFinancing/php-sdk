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
        $url = getenv('TEST_WEGETFINANCING_URL');

        $this->assertIsNotBool($username);
        $this->assertIsNotBool($password);
        $this->assertIsNotBool($merchantId);
        $this->assertIsNotBool($url);

        $auth = AuthEntity::make([
            'username' => $username,
            'password' => $password,
            'merchantId' => $merchantId,
            'url' => $url,
        ]);

        $this->sut = new Client($auth);
    }

    public function testSuccessfullyRequestNewLoanCall(): void
    {
        $loanRequest = LoanRequestEntity::make(LoanRequestEntityTest::VALID_ITEM_1['entity']);
        $response = $this->sut->requestNewLoan($loanRequest);

        print_r($response->getData());

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
        $url = getenv('TEST_WEGETFINANCING_URL_V3');

        $this->assertIsNotBool($username);
        $this->assertIsNotBool($password);
        $this->assertIsNotBool($merchantId);
        $this->assertIsNotBool($url);

        $auth = AuthEntity::make([
            'username' => $username,
            'password' => $password,
            'merchantId' => $merchantId,
            'url' => $url,
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

        $username = getenv('TEST_USERNAME');
        $password = getenv('TEST_PASSWORD');
        $merchantId = getenv('TEST_MERCHANT_ID');
        $url = getenv('TEST_WEGETFINANCING_URL_V3');

        $this->assertIsNotBool($username);
        $this->assertIsNotBool($password);
        $this->assertIsNotBool($merchantId);
        $this->assertIsNotBool($url);

        $auth = AuthEntity::make([
            'username' => $username,
            'password' => $password,
            'merchantId' => $merchantId,
            'url' => $url,
        ]);

        $this->sut = new Client($auth);

        $this->assertArrayHasKey('invId', $loanResponse->getData());

        $data = UpdateShippingStatusRequestEntityTest::VALID_ITEM_1['entity'];
        $data['invId'] = $loanResponse->getData()['invId'];

        $updateRequest = UpdateShippingStatusRequestEntity::make($data);

        $response = $this->sut->updateStatus($updateRequest);

        $this->assertTrue($response->getIsSuccess());

        $this->assertEquals(204, $response->getCode());
    }
}
