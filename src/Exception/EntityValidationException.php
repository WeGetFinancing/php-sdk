<?php

declare(strict_types=1);

namespace App\Exception;

class EntityValidationException extends \Exception
{
    public const INVALID_ENTITY_DATA_MESSAGE = "The data supplied to initialise the entity are invalid";

    public const TYPE_ERROR_INIT_ENTITY_ABSTRACT_CODE = 1;

    public const VALIDATION_VIOLATION_INIT_ENTITY_ABSTRACT_CODE = 2;

    public const VALIDATION_VIOLATION_INIT_MONEY_ENTITY_CODE = 3;

    public const VALIDATION_VIOLATION_INIT_MONEY_ENTITY_MESSAGE = "value should not be equal or less than zero.";

    public const VALIDATION_VIOLATION_IS_VALID_MONEY_ENTITY_CODE = 4;

    /**
     * @var string[]
     */
    protected array $violations;

    /**
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     * @param string[] $violations
     */
    public function __construct(
        string $message = "",
        int $code = 0,
        \Throwable $previous = null,
        array $violations = []
    ) {
        parent::__construct($message, $code, $previous);
        $this->violations = $violations;
    }

    /**
     * @return string[]
     */
    public function getViolations(): array
    {
        return $this->violations;
    }
}
