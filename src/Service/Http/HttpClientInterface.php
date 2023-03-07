<?php

declare(strict_types=1);

namespace WeGetFinancing\SDK\Service\Http;

use WeGetFinancing\SDK\Entity\Response\ResponseEntity;

interface HttpClientInterface
{
    /**
     * Used to be replaced into path or url
     */
    public const MERCHANT_ID_REPLACE = '%MERCHANT_ID%';

    /**
     * Execute a http request and return a ResponseEntity
     *
     * @param string $verb
     * @param string $path
     * @param array<int|string, mixed> $data
     * @return ResponseEntity
     */
    public function request(string $verb, string $path, array $data): ResponseEntity;
}
