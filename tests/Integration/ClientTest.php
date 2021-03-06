<?php

declare(strict_types=1);

namespace Integration;

use WeGetFinancing\SDK\Client;
use WeGetFinancing\SDK\Entity\Request\AuthRequestEntity;
use WeGetFinancing\SDK\Entity\Request\LoanRequestEntity;
use WeGetFinancing\SDK\Entity\Response\ErrorResponseEntity;
use WeGetFinancing\SDK\Entity\Response\SuccessResponseEntity;
use WeGetFinancing\SDK\Exception\EntityValidationException;
use Functional\Entity\Request\LoanRequestEntityTest;
use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client as HttpClient;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

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
            'url' => $url,
        ]);
        $http = new HttpClient();
        $this->sut = new Client(
            $auth,
            $http
        );
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testSuccessfullyIntegrationCall(): void
    {
        $loanRequest = LoanRequestEntity::make(LoanRequestEntityTest::VALID_ITEM_1['entity']);
        $response = $this->sut->requestNewLoan($loanRequest);

        $this->assertTrue($response->getIsSuccess());
        $this->assertSame('200', $response->getCode());
        $this->assertInstanceOf(SuccessResponseEntity::class, $response->getSuccess());
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testSuccessfullyIntegrationCallFromMake(): void
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
            'url' => $url,
        ]);

        $loanRequest = LoanRequestEntity::make(LoanRequestEntityTest::VALID_ITEM_2['entity']);
        $response = Client::make($auth)->requestNewLoan($loanRequest);

        $this->assertTrue($response->getIsSuccess());
        $this->assertSame('200', $response->getCode());
        $this->assertInstanceOf(SuccessResponseEntity::class, $response->getSuccess());
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testUnsuccessfullyIntegrationCallWithServerError(): void
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
            'url' => $url,
        ]);

        $http = $this->createStub(ClientInterface::class);
        $response = $this->createStub(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(500);
        $stream = $this->createStub(StreamInterface::class);
        $stream->method('getContents')->willReturn('<');
        $response->method('getBody')->willReturn($stream);
        $http->method('request')->willReturn($response);

        $this->sut = new Client(
            $auth,
            $http
        );

        $loanRequest = LoanRequestEntity::make(LoanRequestEntityTest::VALID_ITEM_2['entity']);
        $response = $this->sut->requestNewLoan($loanRequest);

        $this->assertFalse($response->getIsSuccess());
        $this->assertSame('500', $response->getCode());
        $this->assertInstanceOf(ErrorResponseEntity::class, $response->getError());
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testUnsuccessfullyIntegrationCall(): void
    {
        $loanRequest = LoanRequestEntity::make(LoanRequestEntityTest::VALID_ITEM_1['entity']);
        $loanRequest->email = 'invalid-email';
        $response = $this->sut->requestNewLoan($loanRequest);

        $this->assertFalse($response->getIsSuccess());
        $this->assertSame('400', $response->getCode());
        $this->assertInstanceOf(ErrorResponseEntity::class, $response->getError());
    }
}
