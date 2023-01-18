<?php

namespace WeGetFinancing\SDK\Command;

use WeGetFinancing\SDK\Entity\AuthEntity;
use WeGetFinancing\SDK\Entity\Request\AbstractRequestEntity;
use WeGetFinancing\SDK\Entity\Response\ResponseEntity;
use WeGetFinancing\SDK\Exception\EntityValidationException;
use WeGetFinancing\SDK\Service\Http\V3\HttpClientV3;

class UpdateShippingStatusCommand extends AbstractCommand
{
    public const UPDATE_SHIPPING_STATUS_VERB = 'POST';

    public const UPDATE_SHIPPING_STATUS_PATH = '/lead/_INV_ID_/shipping_status';

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param AuthEntity $authEntity
     * @return UpdateShippingStatusCommand
     */
    public static function make(AuthEntity $authEntity): self
    {
        $client = HttpClientV3::make($authEntity);
        return new UpdateShippingStatusCommand($client);
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
        return $this->httpClient->request(
            self::UPDATE_SHIPPING_STATUS_VERB,
            self::UPDATE_SHIPPING_STATUS_PATH,
            $requestEntity->getWeGetFinancingRequest()
        );
    }
}
