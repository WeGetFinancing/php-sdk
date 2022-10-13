<?php

declare(strict_types=1);

namespace WeGetFinancing\SDK\Exception;

class EntityValidationException extends \Exception
{
    public const INVALID_ENTITY_DATA_MESSAGE = "The data supplied to initialise the entity are invalid";

    public const TYPE_ERROR_INIT_ENTITY_ABSTRACT_CODE = 1;

    public const VALIDATION_VIOLATION_INIT_ENTITY_ABSTRACT_CODE = 2;

    public const VALIDATION_VIOLATION_INIT_MONEY_ENTITY_CODE = 3;

    public const VALIDATION_VIOLATION_INIT_MONEY_ENTITY_MESSAGE = "value should not be equal or less than zero.";

    public const VALIDATION_VIOLATION_IS_VALID_MONEY_ENTITY_CODE = 4;

    public const TYPE_ERROR_INIT_ENTITY_CART_ITEM_CODE = 5;

    public const TYPE_ERROR_INIT_ENTITY_LOAN_REQUEST_CODE = 6;

    public const UNDEFINED_RESPONSE_KEY_LOAN_RESPONSE_MESSAGE =
        "The loan response entity needs a valid response array to be initialised.";

    public const UNDEFINED_RESPONSE_KEY_LOAN_RESPONSE_CODE = 7;

    /**
     * @var string[]
     */
    protected array $violations;

    /**
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     * @param array $violations
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
