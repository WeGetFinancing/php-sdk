<?php

declare(strict_types=1);

namespace Unit\Validator\Constraints;

use WeGetFinancing\SDK\Validator\Constraints\IsAValidUSZipCode;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * @unit
 */
final class IsAValidUsZipCodeTest extends TestCase
{
    public const VALID_MESSAGE = 'The US Zip Code "{{ string }}" it is illegal: it can only contain 5 ' .
    'numbers optionally followed by a dash and 4 numbers.';

    public const VALID_ERROR_NAMES = [
        IsAValidUSZipCode::IS_A_VALID_US_ZIP_CODE_ERROR => 'IS_A_VALID_US_ZIP_CODE_ERROR',
    ];

    protected IsAValidUSZipCode $sut;

    public function testTheClassWillHaveTheExpectedData(): void
    {
        $this->sut = new IsAValidUSZipCode();

        $this->assertSame(
            IsAValidUSZipCode::IS_A_VALID_US_ZIP_CODE_ERROR,
            $this->sut::IS_A_VALID_US_ZIP_CODE_ERROR
        );
        $this->assertSame(
            self::VALID_MESSAGE,
            $this->sut->message
        );

        $reflection = new ReflectionClass($this->sut);
        $property = $reflection->getProperty('errorNames');
        $property->setAccessible(true);
        $this->assertSame(
            self::VALID_ERROR_NAMES,
            $property->getValue($this->sut)
        );
    }
}
