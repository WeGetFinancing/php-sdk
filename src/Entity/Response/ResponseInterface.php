<?php

namespace WeGetFinancing\SDK\Entity\Response;

interface ResponseInterface
{
    /**
     * Return an array with the data of the response
     *
     * @return array<int|string, mixed>
     */
    public function toArray(): array;
}
