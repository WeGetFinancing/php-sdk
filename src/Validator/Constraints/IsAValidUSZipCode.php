<?php

declare(strict_types=1);

namespace WeGetFinancing\SDK\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsAValidUSZipCode extends Constraint
{
    public const IS_A_VALID_US_ZIP_CODE_ERROR = '42e5bea6-531a-4349-98d5-0c86040ce44e';

    /**
     * @var string[]
     */
    protected static $errorNames = [
        self::IS_A_VALID_US_ZIP_CODE_ERROR => 'IS_A_VALID_US_ZIP_CODE_ERROR',
    ];

    public string $message = 'The US Zip Code "{{ string }}" it is illegal: it can only contain 5 ' .
        'numbers optionally followed by a dash and 4 numbers.';
}
