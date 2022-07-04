<?php

declare(strict_types=1);

namespace Unit\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;
use WeGetFinancing\SDK\Validator\Constraints\IsAValidUSZipCodeValidator;
use WeGetFinancing\SDK\Validator\Constraints\IsAValidUSZipCode;
use stdClass;

/**
 * @unit
 */
final class IsAValidUSZipCodeValidatorTest extends ConstraintValidatorTestCase
{
    public function testValidatorIsCorrectlyInstantiated(): void
    {
        $this->validator->validate(null, new IsAValidUSZipCode());

        $this->expectException(UnexpectedTypeException::class);
        $this->validator->validate('wrong-value', $this->createMock(Constraint::class));
    }

    public function testNullIsValid(): void
    {
        $this->validator->validate(null, new IsAValidUSZipCode());

        $this->assertNoViolation();
    }

    public function testEmptyStringIsValid(): void
    {
        $this->validator->validate('', new IsAValidUSZipCode());

        $this->assertNoViolation();
    }

    public function testStringableObjectsAreValid(): void
    {
        $this->validator->validate($this->getEmptyStringObject(), new IsAValidUSZipCode());

        $this->assertNoViolation();
    }

    public function testUnexpectedValueExceptionWillBeThrowForNotStringableClasses(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->validator->validate(new stdClass(), new IsAValidUSZipCode());
    }

    /**
     * @return iterable<array<string>>
     */
    public function getValidUSZipCodes(): iterable
    {
        yield ['66699'];
        yield ['00000'];
        yield ['55555-4444'];
        yield ['12345-6789'];
    }

    /**
     * @dataProvider getValidUSZipCodes
     */
    public function testValidDateIntervals(string $zipCode): void
    {
        $this->validator->validate($zipCode, new IsAValidUSZipCode());

        $this->assertNoViolation();
    }

    /**
     * @return iterable<array<string>>
     */
    public function getInvalidUSZipCodes(): iterable
    {
        yield ['4444'];
        yield ['666666'];
        yield ['55555-55555'];
        yield ['4444-55555'];
        yield ['444455555'];
    }

    /**
     * @dataProvider getInvalidUSZipCodes
     */
    public function testInvalidDateIntervals(string $zipCode): void
    {
        $constraint = new IsAValidUSZipCode([
            'message' => 'myMessage',
        ]);

        $this->validator->validate($zipCode, $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', '"'.$zipCode.'"')
            ->setCode(IsAValidUSZipCode::IS_A_VALID_US_ZIP_CODE_ERROR)
            ->assertRaised();
    }

    protected function createValidator(): IsAValidUSZipCodeValidator
    {
        return new IsAValidUSZipCodeValidator();
    }

    private function getEmptyStringObject(): stdClass
    {
        return new class () extends stdClass {
            public function __toString(): string
            {
                return '';
            }
        };
    }
}
