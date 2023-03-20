<?php

declare(strict_types=1);

namespace WeGetFinancing\SDK\Service\Http;

use GuzzleHttp\ClientInterface;
use WeGetFinancing\SDK\Entity\AuthEntity;

abstract class AbstractHttpClient implements HttpClientInterface
{
    public const URL_API_V1_PROD = 'https://api.wegetfinancing.com';
    public const URL_API_V1_SANDBOX = 'https://api.sandbox.wegetfinancing.com';
    public const URL_API_V3_PROD = 'https://apisrv.wegetfinancing.com';
    public const URL_API_V3_SANDBOX = 'https://apisrv.sandbox.wegetfinancing.com';

    /**
     * @param AuthEntity $authEntity
     * @param ClientInterface $httpClient
     */
    public function __construct(
        public AuthEntity $authEntity,
        protected ClientInterface $httpClient
    ) {
    }

    protected function getBaseUrlApiV1(): string
    {
        return (true === $this->authEntity->isProd()) ? self::URL_API_V1_PROD : self::URL_API_V1_SANDBOX;
    }

    protected function getBaseUrlApiV3(): string
    {
        return (true === $this->authEntity->isProd()) ? self::URL_API_V3_PROD : self::URL_API_V3_SANDBOX;
    }

    protected function getUrlApiV1FromPath(string $path): string
    {
        return $this->getBaseUrlApiV1() . $path;
    }

    protected function getUrlApiV3FromPath(string $path): string
    {
        return $this->getBaseUrlApiV3() . $path;
    }

    protected function getMerchantIdPath(string $path): string
    {
        return str_replace(
            HttpClientInterface::MERCHANT_ID_REPLACE,
            $this->authEntity->getMerchantId(),
            $path
        );
    }

    protected function getUrlApiV1FromMerchantIdPath(string $path): string
    {
        return $this->getUrlApiV1FromPath(
            $this->getMerchantIdPath($path)
        );
    }
}
