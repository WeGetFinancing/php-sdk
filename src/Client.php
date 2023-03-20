<?php

declare(strict_types=1);

namespace WeGetFinancing\SDK;

use WeGetFinancing\SDK\Command\RequestNewLoanCommand;
use WeGetFinancing\SDK\Command\UpdateShippingStatusCommand;
use WeGetFinancing\SDK\Entity\AuthEntity;
use WeGetFinancing\SDK\Entity\Request\LoanRequestEntity;
use WeGetFinancing\SDK\Entity\Request\UpdateShippingStatusRequestEntity;
use WeGetFinancing\SDK\Entity\Response\ResponseEntity;
use WeGetFinancing\SDK\Exception\EntityValidationException;

class Client
{
    public function __construct(
        protected AuthEntity $authEntity
    ) { }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param AuthEntity $authEntity
     * @return Client
     */
    public static function make(AuthEntity $authEntity): Client
    {
        return new Client($authEntity);
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param LoanRequestEntity $requestEntity
     * @throws EntityValidationException
     * @return ResponseEntity
     */
    public function requestNewLoan(LoanRequestEntity $requestEntity): ResponseEntity
    {
        $command = RequestNewLoanCommand::make($this->authEntity);
        return $command->execute($requestEntity);
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param UpdateShippingStatusRequestEntity $requestEntity
     * @return ResponseEntity
     */
    public function updateStatus(UpdateShippingStatusRequestEntity $requestEntity): ResponseEntity
    {
        $command = UpdateShippingStatusCommand::make($this->authEntity);
        return $command->execute($requestEntity);
    }
}
