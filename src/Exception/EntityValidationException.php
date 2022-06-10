<?php

declare(strict_types=1);

namespace App\Exception;

class EntityValidationException extends \Exception
{
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
