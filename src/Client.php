<?php

declare(strict_types=1);

namespace App;

use App\Entity\Request\AuthRequestEntity;
use App\Entity\Request\LoanRequestEntity;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

class Client
{
    public const LOAN_REQUEST_METHOD = 'POST';

    protected AuthRequestEntity $auth;

    protected ClientInterface $httpClient;

    protected string $url;

    /**
     * @param AuthRequestEntity $auth
     * @param ClientInterface $httpClient
     * @param string $url
     */
    public function __construct(
        AuthRequestEntity $auth,
        ClientInterface $httpClient,
        string $url
    ) {
        $this->auth = $auth;
        $this->httpClient = $httpClient;
        $this->url = $url;
    }

//    /**
//     * @SuppressWarnings(PHPMD.StaticAccess)
//     */
//    public static function make(array $data = null): AuthRequestEntity
//    {
//        return new Client(
//
//        );
//    }

    /**
     * @param LoanRequestEntity $loanRequestEntity
     * @throws GuzzleException
     * @return void
     */
    public function requestNewLoan(LoanRequestEntity $loanRequestEntity)
    {
        $this->httpClient->request(
            self::LOAN_REQUEST_METHOD,
            $this->url . $this->auth->getRequestNewLoanPath(),
            [
                'http_errors' => false,
                'headers' => $this->auth->getWeGetFinancingRequest(),
                'json' => $loanRequestEntity->getWeGetFinancingRequest(),
            ]
        );
//        $status = $response->getStatusCode();
//        $content = $response->getBody()->getContents();

//        if (200 === $status) {
//            $data = json_decode($content);
//            $error = json_last_error();
//            if (JSON_ERROR_NONE === $error) {
//                return LoanResponseEntity::make(
//                    array_merge($data[
//                    'status' => $status,
//
//                ])
//            }
//        }
//        print_r($response->getStatusCode());
//        print_r($content);
    }
}
