<?php

namespace WeGetFinancing\SDK\Service\Http;

use GuzzleHttp\ClientInterface;
use WeGetFinancing\SDK\Entity\AuthEntity;

abstract class AbstractHttpClient implements HttpClientInterface
{
    public AuthEntity $authEntity;

    protected ClientInterface $httpClient;

    /**
     * @param AuthEntity $authEntity
     * @param ClientInterface $httpClient
     */
    public function __construct(
        AuthEntity $authEntity,
        ClientInterface $httpClient
    ) {
        $this->authEntity = $authEntity;
        $this->httpClient = $httpClient;
    }

    protected function getUrlFromPath(string $path): string
    {
        return $this->authEntity->getUrl() . $path;
    }

    protected function getMerchantIdPath(string $path): string
    {
        return str_replace(
            HttpClientInterface::MERCHANT_ID_REPLACE,
            $this->authEntity->getMerchantId(),
            $path
        );
    }

    protected function getUrlFromMerchantIdPath(string $path): string
    {
        return $this->getUrlFromPath(
            $this->getMerchantIdPath($path)
        );
    }
}
