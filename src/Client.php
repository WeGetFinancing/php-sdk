<?php

declare(strict_types=1);

namespace App;

use App\Entity\Request\AuthRequestEntity;
use App\Entity\Request\LoanRequestEntity;
use App\Entity\Response\LoanResponseEntity;
use App\Exception\EntityValidationException;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client as HttpClient;

class Client
{
    public const LOAN_REQUEST_METHOD = 'POST';

    public const DATA_CONTENT_ERROR_ERROR = 'unknown-error';

    public const DATA_CONTENT_ERROR_MESSAGE = 'Impossible to decode response content.';

    public const DATA_CONTENT_ERROR_STAMP = '0x0';

    public const DATA_CONTENT_ERROR_TYPE = 'error';

    protected AuthRequestEntity $auth;

    protected string $url;

    protected ClientInterface $httpClient;

    /**
     * @param AuthRequestEntity $auth
     * @param string $url
     * @param ClientInterface $httpClient
     */
    public function __construct(
        AuthRequestEntity $auth,
        string $url,
        ClientInterface $httpClient
    ) {
        $this->auth = $auth;
        $this->url = $url;
        $this->httpClient = $httpClient;
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public static function make(AuthRequestEntity $auth, string $url): Client
    {
        return new Client(
            $auth,
            $url,
            new HttpClient()
        );
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param LoanRequestEntity $loanRequestEntity
     * @throws GuzzleException|EntityValidationException
     * @return LoanResponseEntity
     */
    public function requestNewLoan(LoanRequestEntity $loanRequestEntity): LoanResponseEntity
    {
        $response = $this->httpClient->request(
            self::LOAN_REQUEST_METHOD,
            $this->url . $this->auth->getRequestNewLoanPath(),
            [
                'http_errors' => false,
                'headers' => $this->auth->getWeGetFinancingRequest(),
                'json' => $loanRequestEntity->getWeGetFinancingRequest(),
            ]
        );
        $status = $response->getStatusCode();
        $content = $response->getBody()->getContents();
        $data = json_decode($content, true);
        $error = json_last_error();

        if (JSON_ERROR_NONE !== $error) {
            return LoanResponseEntity::make([
                'isSuccess' => false,
                'code' => (string) $status,
                'response' => [
                    'error' => self::DATA_CONTENT_ERROR_ERROR,
                    'message' => self::DATA_CONTENT_ERROR_MESSAGE,
                    'type' => self::DATA_CONTENT_ERROR_TYPE,
                    'stamp' => self::DATA_CONTENT_ERROR_STAMP,
                ],
            ]);
        }

        if ($status >= 200  && $status < 300) {
            return LoanResponseEntity::make([
                'isSuccess' => true,
                'code' => (string) $status,
                'response' => $data,
            ]);
        }

        return LoanResponseEntity::make([
            'isSuccess' => false,
            'code' => (string) $status,
            'response' => $data,
        ]);
    }
}
