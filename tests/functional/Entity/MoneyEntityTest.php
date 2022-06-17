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
            'expected' => '12.56',
            'data' => [ 'value' => '12.56' ]
        ]];
        yield [[
            'expected' => '5.00',
            'data' => [ 'value' => 5 ]
        ]];
        yield [[
            'expected' => '7.00',
            'data' => [ 'value' => 7.0 ]
        ]];
        yield [[
            'expected' => '5.10',
            'data' => [ 'value' => '5.1' ]
        ]];
        yield [[
            'expected' => '4.20',
            'data' => [ 'value' => 4.2 ]
        ]];
        yield [[
            'expected' => '66.99',
            'data' => [ 'value' => 66.999999563 ]
        ]];
        yield [[
            'expected' => '0.00',
            'data' => [ 'value' => 0.0 ]
        ]];
        yield [[
            'expected' => '0.00',
            'data' => [ 'value' => 0 ]
        ]];
        yield [[
            'expected' => '0.00',
            'data' => [ 'value' => '0' ]
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
        $this->sut = MoneyEntity::make($data['data']);

        $this->assertSame($data['expected'], $this->sut->getValue());
        $this->assertSame(['value' => $data['expected']], $this->sut->getWeGetFinancingRequest());
    }

    /**
     * @return iterable<int, array<array<string, string|array<int|string, mixed>>>>
     */
    public function getInvalidMoneyEntityData(): iterable
    {
        yield [[
            'entity' => [ 'value' => '-12.56' ],
            'violations' => [ 'The money value should be either positive or zero.' ]
        ]];
        yield [[
            'entity' => [ 'value' => -9 ],
            'violations' => [ 'The money value should be either positive or zero.' ]
        ]];
        yield [[
            'entity' => [ 'value' => 'A' ],
            'violations' => [ 'The money value is not a valid numeric.' ]
        ]];
        yield [[
            'entity' => [ 'value' => '4.b' ],
            'violations' => [ 'The money value is not a valid numeric.' ]
        ]];
        yield [[
            'entity' => [ 'value' => '' ],
            'violations' => [
                'The money value is not a valid numeric.',
                'The money value should be either positive or zero.',
                'The money value should not be blank.'
            ]
        ]];
        yield [[
            'entity' => [ 'value' => null ],
            'violations' => [
                'Cannot assign null to property App\Entity\MoneyEntity::$value of type string'
            ]
        ]];
        yield [[
            'entity' => [ 'value' => 'no' ],
            'violations' => [ 'The money value is not a valid numeric.' ]
        ]];
        yield [[
            'entity' => [ 'value' => true ],
            'violations' => [ 'Cannot assign bool to property App\Entity\MoneyEntity::$value of type string' ]
        ]];
    }

    /**
     * @dataProvider getInvalidMoneyEntityData
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param array<array<string, array<int|string, string>>> $data
     * @return void
     */
    public function testMakeWithDataWillFailAsExpected(array $data): void
    {
        try {
            MoneyEntity::make($data['entity']);
        } catch (EntityValidationException $exception) {
            $this->assertSame(EntityValidationException::class, get_class($exception));
            $this->assertSame($data['violations'], $exception->getViolations());
        }
    }
}
