<?php

declare(strict_types=1);

namespace functional\Entity;

use App\Entity\MoneyEntity;
use App\Exception\EntityValidationException;
use PHPUnit\Framework\TestCase;

class MoneyEntityTest extends TestCase
{
    protected MoneyEntity $sut;

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testMakeWithoutDataWillWorkAsExpected(): void
    {
        $this->sut = MoneyEntity::make();
        $this->assertInstanceOf(MoneyEntity::class, $this->sut);
    }

    /**
     * @return iterable<int, array<array<string, string|array<string, mixed>>>>
     */
    public function getCorrectMoneyEntityData(): iterable
    {
        yield [[
            "expected" => "12.56",
            "data" => [ "value" => "12.56" ]
        ]];
        yield [[
            "expected" => "5.00",
            "data" => [ "value" => 5 ]
        ]];
        yield [[
            "expected" => "7.00",
            "data" => [ "value" => 7.0 ]
        ]];
        yield [[
            "expected" => "66.99",
            "data" => [ "value" => 66.999999563 ]
        ]];
    }

    /**
     * @dataProvider getCorrectMoneyEntityData
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param array<array<string, string|array<string, float>>> $data
     * @return void
     * @throws EntityValidationException
     */
    public function testMakeWithDataWillSucceedAsExpected(array $data): void
    {
        $this->sut = MoneyEntity::make($data["data"]);

        $this->assertSame($data["expected"], $this->sut->getValue());
        $this->assertSame(["value" => $data["expected"]], $this->sut->getWeGetFinancingRequest());
    }

    /**
     * @return iterable<int, array<array<string, string|array<int|string, string>>>>
     */
    public function getInvalidMoneyEntityData(): iterable
    {
        yield [[
            "data" => [ "value" => "-12.56" ],
            "violations" => [ "" ]
        ]];
        yield [[
            "data" => [ "value" => "0" ],
            "violations" => [ "" ]
        ]];
        yield [[
            "data" => [ "value" => "-12.56" ],
            "violations" => [ "" ]
        ]];
        yield [[
            "data" => [ "value" => "0" ],
            "violations" => [ "" ]
        ]];
        yield [[
            "data" => [ "value" => "-12.56" ],
            "violations" => [ "" ]
        ]];
        yield [[
            "data" => [ "value" => "0" ],
            "violations" => [ "" ]
        ]];
    }
}
