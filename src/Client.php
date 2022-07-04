<?php

declare(strict_types=1);

namespace WeGetFinancing\SDK;

use WeGetFinancing\SDK\Entity\Request\AuthRequestEntity;
use WeGetFinancing\SDK\Entity\Request\LoanRequestEntity;
use WeGetFinancing\SDK\Entity\Response\LoanResponseEntity;
use WeGetFinancing\SDK\Exception\EntityValidationException;
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

    protected ClientInterface $httpClient;

    /**
     * @param AuthRequestEntity $auth
     * @param ClientInterface $httpClient
     */
    public function __construct(
        AuthRequestEntity $auth,
        ClientInterface $httpClient
    ) {
        $this->auth = $auth;
        $this->httpClient = $httpClient;
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public static function make(AuthRequestEntity $auth): Client
    {
        return new Client(
            $auth,
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
            $this->auth->getRequestNewLoanUrl(),
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
