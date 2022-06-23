<?php

declare(strict_types=1);

namespace integration;

use App\Client;
use App\Entity\Request\AuthRequestEntity;
use App\Entity\Request\LoanRequestEntity;
use functional\Entity\Request\LoanRequestEntityTest;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client as HttpClient;

final class ClientTest extends TestCase
{
    private Client $sut;

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
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

        $auth = AuthRequestEntity::make([
            'username' => $username,
            'password' => $password,
            'merchantId' => $merchantId,
        ]);
        $http = new HttpClient();
        $this->sut = new Client(
            $auth,
            $http,
            (string) $url
        );
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testSuccessfullyIntegrationCall(): void
    {
        $loanRequest = LoanRequestEntity::make(LoanRequestEntityTest::VALID_ITEM_1['entity']);
//        $loanRequest->phone = "1234";
//        $loanRequest->email = "invalid";
        $this->sut->requestNewLoan($loanRequest);
    }
}
