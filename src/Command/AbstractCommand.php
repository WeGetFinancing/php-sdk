<?php

declare(strict_types=1);

namespace WeGetFinancing\SDK\Command;

use WeGetFinancing\SDK\Service\Http\HttpClientInterface;

abstract class AbstractCommand implements CommandInterface
{
    public function __construct(protected HttpClientInterface $httpClient)
    {
    }
}
