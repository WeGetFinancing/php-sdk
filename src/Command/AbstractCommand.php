<?php

declare(strict_types=1);

namespace WeGetFinancing\SDK\Command;

use WeGetFinancing\SDK\Service\Http\HttpClientInterface;

abstract class AbstractCommand implements CommandInterface
{
    protected HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }
}
