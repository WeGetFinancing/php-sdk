<?php

declare(strict_types=1);

namespace WeGetFinancing\SDK\Command;

use WeGetFinancing\SDK\Entity\AuthEntity;
use WeGetFinancing\SDK\Entity\Request\AbstractRequestEntity;
use WeGetFinancing\SDK\Entity\Response\LoanErrorResponseEntity;
use WeGetFinancing\SDK\Entity\Response\LoanSuccessResponseEntity;
use WeGetFinancing\SDK\Entity\Response\ResponseEntity;
use WeGetFinancing\SDK\Exception\EntityValidationException;
use WeGetFinancing\SDK\Service\Http\HttpClientInterface;
use WeGetFinancing\SDK\Service\Http\V1\HttpClientV1;

class RequestNewLoanCommand extends AbstractCommand
{
    public const LOAN_REQUEST_VERB = 'POST';
    public const LOAN_REQUEST_PATH = '/merchant/' . HttpClientInterface::MERCHANT_ID_REPLACE . '/requests';

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param AuthEntity $authEntity
     * @return RequestNewLoanCommand
     */
    public static function make(AuthEntity $authEntity): self
    {
        $client = HttpClientV1::make($authEntity);
        return new RequestNewLoanCommand($client);
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param AbstractRequestEntity $requestEntity
     * @throws EntityValidationException
     * @return ResponseEntity
     */
    public function execute(AbstractRequestEntity $requestEntity): ResponseEntity
    {
        $response = $this->httpClient->request(
            self::LOAN_REQUEST_VERB,
            self::LOAN_REQUEST_PATH,
            $requestEntity->getWeGetFinancingRequest()
        );

        $dataEntity = (true === $response->getIsSuccess())
            ? LoanSuccessResponseEntity::make($response->getData())
            : LoanErrorResponseEntity::make($response->getData());

        $response->setData($dataEntity->toArray());

        return $response;
    }
}
