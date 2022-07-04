<?php

declare(strict_types=1);

namespace WeGetFinancing\SDK\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class IsAValidUSZipCodeValidator extends ConstraintValidator
{
    private const PATTERN = '/^[0-9]{5}(?:-[0-9]{4})?$/';

    /**
     * @param  mixed $value
     * @param  Constraint $constraint
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof IsAValidUSZipCode) {
            throw new UnexpectedTypeException($constraint, IsAValidUSZipCode::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedValueException($value, 'string');
        }

        $value = (string) $value;
        if ('' === $value) {
            return;
        }

        if (!preg_match(self::PATTERN, $value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(IsAValidUSZipCode::IS_A_VALID_US_ZIP_CODE_ERROR)
                ->addViolation();
        }
    }
}
