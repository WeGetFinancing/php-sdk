<?php

declare(strict_types=1);

namespace WeGetFinancing\SDK\Entity\Response;

interface ResponseInterface
{
    /**
     * Return an array with the data of the response
     *
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
