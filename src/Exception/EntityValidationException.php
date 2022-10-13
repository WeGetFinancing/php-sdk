<?php

declare(strict_types=1);

namespace WeGetFinancing\SDK\Exception;

use Stringable;

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
     * @var array<int|string, string|mixed>
     */
    protected array $violations;

    /**
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     * @param array<int|string, string|mixed> $violations
     */
    public function __construct(
        string $message = "",
        int $code = 0,
        \Throwable $previous = null,
        array $violations = []
    ) {
        parent::__construct($message, $code, $previous);
        if (
            true === array_key_exists('field', $violations) &&
            true === array_key_exists('message', $violations)
        ) {
            $violations = [[
                'field' => $violations['field'],
                'message' => $violations['message'],
            ]];
        }

        $this->violations = $violations;
    }

    /**
     * @return array<int|string, string|mixed>
     */
    public function getViolations(): array
    {
        return $this->violations;
    }
}
