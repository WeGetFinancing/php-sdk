<?php

declare(strict_types=1);

namespace WeGetFinancing\SDK\Service\Http\V1;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use WeGetFinancing\SDK\Entity\AuthEntity;
use WeGetFinancing\SDK\Exception\EntityValidationException;
use WeGetFinancing\SDK\Entity\Response\ResponseEntity;
use WeGetFinancing\SDK\Service\Http\AbstractHttpClient;

class HttpClientV1 extends AbstractHttpClient
{
    public const DEFAULT_ERROR_ERROR = 'unknown-error';
    public const DEFAULT_ERROR_MESSAGE = 'Impossible to decode response content.';
    public const DEFAULT_ERROR_STAMP = '0x0';
    public const DEFAULT_ERROR_TYPE = 'error';
    public const HEADERS = [
        'Content-Type' => 'application/json',
        'Accept' =>  'application/json',
        'Authorization' => 'Basic ',
    ];

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param AuthEntity $authEntity
     * @return HttpClientV1
     */
    public static function make(AuthEntity $authEntity): self
    {
        return new HttpClientV1(
            $authEntity,
            new Client()
        );
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @throws GuzzleException
     * @throws EntityValidationException
     */
    public function request(string $verb, string $path, array $data): ResponseEntity
    {
        $response = $this->httpClient->request(
            $verb,
            $this->getUrlApiV1FromMerchantIdPath($path),
            [
                'http_errors' => false,
                'headers' => $this->getHeaders(),
                'json' => $data,
            ]
        );
        $status = $response->getStatusCode();
        $content = $response->getBody()->getContents();
        $data = json_decode($content, true);
        $error = json_last_error();

        if (JSON_ERROR_NONE !== $error) {
            return ResponseEntity::make([
                'isSuccess' => false,
                'code' => (string) $status,
                'data' => [
                    'error' => self::DEFAULT_ERROR_ERROR,
                    'message' => self::DEFAULT_ERROR_MESSAGE,
                    'type' => self::DEFAULT_ERROR_TYPE,
                    'stamp' => self::DEFAULT_ERROR_STAMP,
                ],
            ]);
        }

        if ($status >= 200  && $status < 300) {
            return ResponseEntity::make([
                'isSuccess' => true,
                'code' => (string) $status,
                'data' => $data,
            ]);
        }

        return ResponseEntity::make([
            'isSuccess' => false,
            'code' => (string) $status,
            'data' => $data,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    protected function getHeaders(): array
    {
        $header = self::HEADERS;
        $header['Authorization'] = $header['Authorization'] . $this->getBase64Credentials();
        return $header;
    }

    protected function getBase64Credentials(): string
    {
        return base64_encode($this->authEntity->getUsername() . ':' . $this->authEntity->getPassword());
    }
}
