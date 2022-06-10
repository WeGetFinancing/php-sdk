<?php

declare(strict_types=1);

namespace unit;

use PHPUnit\Framework\TestCase;
use App\Client;

final class ClientTest extends TestCase
{
    private Client $sut;

    public function setUp(): void
    {
        $this->sut = new Client();
    }

    public function testClientIsInstantiatedAndUnitTestAreRunning(): void
    {
        $this->assertInstanceOf(Client::class, $this->sut);
    }
}
