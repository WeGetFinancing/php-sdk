<?php

declare(strict_types=1);

namespace WeGetFinancing\SDK\Service\Http\V3;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use WeGetFinancing\SDK\Entity\AuthEntity;
use WeGetFinancing\SDK\Entity\Response\ResponseEntity;
use WeGetFinancing\SDK\Exception\EntityValidationException;
use WeGetFinancing\SDK\Service\Http\AbstractHttpClient;

class HttpClientV3 extends AbstractHttpClient
{
    public const HEADERS = [
        'Content-Type' => 'application/json',
        'Accept' =>  'application/json',
    ];

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param AuthEntity $authEntity
     * @return HttpClientV3
     */
    public static function make(AuthEntity $authEntity): self
    {
        return new HttpClientV3(
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
        $token = $this->getToken();

        if ($token instanceof ResponseEntity) {
            return $token;
        }

        $response = $this->httpClient->request(
            $verb,
            $this->getUrlApiV3FromPath($path),
            [
                'http_errors' => false,
                'headers' => $this->getAuthenticatedHeaders($token['access_token']),
                'json' => $data,
            ]
        );
        $status = $response->getStatusCode();
        $content = $response->getBody()->getContents();

        if (false === empty($content)) {
            $data = json_decode($content, true);
            $error = json_last_error();

            if (JSON_ERROR_NONE !== $error) {
                return ResponseEntity::make([
                    'isSuccess' => false,
                    'code' => (string) $status,
                    'data' => [],
                ]);
            }
        }


        if ($status >= 200  && $status < 300) {
            return ResponseEntity::make([
                'isSuccess' => true,
                'code' => (string) $status,
                'data' => [],
            ]);
        }

        return ResponseEntity::make([
            'isSuccess' => false,
            'code' => (string) $status,
            'data' => $data,
        ]);
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @throws GuzzleException
     * @throws EntityValidationException
     */
    public function getToken(): mixed
    {
        $response = $this->httpClient->request(
            'POST',
            $this->getUrlApiV3FromPath('/v3/auth'),
            [
                'http_errors' => false,
                'headers' => self::HEADERS,
                'json' => [
                    'client_id' => $this->authEntity->getUsername(),
                    'client_secret' => $this->authEntity->getPassword(),
                ],
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
                'data' => [],
            ]);
        }

        if (200 === $status) {
            return $data;
        }

        return ResponseEntity::make([
            'isSuccess' => false,
            'code' => (string) $status,
            'data' => $data,
        ]);
    }

    /**
     * @param string $token
     * @return array<string, mixed>
     */
    protected function getAuthenticatedHeaders(string $token): array
    {
        $header = self::HEADERS;
        $header['X-WGT-ACCESS-TOKEN'] = $token;
        return $header;
    }
}
