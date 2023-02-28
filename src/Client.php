<?php

declare(strict_types=1);

namespace WeGetFinancing\SDK;

use WeGetFinancing\SDK\Command\RequestNewLoanCommand;
use WeGetFinancing\SDK\Command\UpdateShippingStatusCommand;
use WeGetFinancing\SDK\Entity\AuthEntity;
use WeGetFinancing\SDK\Entity\Request\LoanRequestEntity;
use WeGetFinancing\SDK\Entity\Request\UpdateShippingStatusRequestEntity;
use WeGetFinancing\SDK\Entity\Response\ResponseEntity;

class Client
{
    protected AuthEntity $authEntity;

    public function __construct(AuthEntity $authEntity)
    {
        $this->authEntity = $authEntity;
    }

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

    public function requestNewLoan(LoanRequestEntity $requestEntity): ResponseEntity
    {
        $command = RequestNewLoanCommand::make($this->authEntity);
        return $command->execute($requestEntity);
    }

    public function updateStatus(UpdateShippingStatusRequestEntity $requestEntity): ResponseEntity
    {
        $command = UpdateShippingStatusCommand::make($this->authEntity);
        return $command->execute($requestEntity);
    }
}
