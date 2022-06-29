<?php

declare(strict_types=1);

namespace App\Entity\Response;

use App\Entity\AbstractEntity;
use App\Exception\EntityValidationException;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Validator\Constraints as Assert;

class LoanResponseEntity extends AbstractEntity
{
    /**
     * @Assert\NotNull(message = "The value of isSuccess should not be null.")
     */
    protected bool $isSuccess;

    /**
     * @Assert\NotBlank(message = "The value of code should not be blank.")
     * @Assert\NotNull(message = "The value of code should not be null.")
     */
    protected string $code;

    protected ?SuccessResponseEntity $success;

    protected ?ErrorResponseEntity $error;

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @throws EntityValidationException
     */
    public static function make(array $data = null): LoanResponseEntity
    {
        return new LoanResponseEntity(
            self::getValidator(),
            new CamelCaseToSnakeCaseNameConverter(),
            $data
        );
    }

    public function getIsSuccess(): bool
    {
        return $this->isSuccess;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getSuccess(): ?SuccessResponseEntity
    {
        return $this->success;
    }

    public function getError(): ?ErrorResponseEntity
    {
        return $this->error;
    }

    /**
     * @param  array<string, mixed> $data
     * @throws EntityValidationException
     * @return self
     */
    public function initFromArray(array $data): self
    {
        if (false === array_key_exists('response', $data)) {
            throw new EntityValidationException(
                EntityValidationException::INVALID_ENTITY_DATA_MESSAGE,
                EntityValidationException::UNDEFINED_RESPONSE_KEY_LOAN_RESPONSE_CODE,
                null,
                [ EntityValidationException::UNDEFINED_RESPONSE_KEY_LOAN_RESPONSE_MESSAGE ]
            );
        }
        $response = $data['response'];
        unset($data['response']);
        parent::initFromArray($data);
        $this->setResponseEntity($response);
        return $this;
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param array<string, mixed> $data
     * @throws EntityValidationException
     * @return void
     */
    protected function setResponseEntity(array $data): void
    {
        if (false === $this->isSuccess) {
            $this->error = ErrorResponseEntity::make($data);
            return;
        }
        $this->success = SuccessResponseEntity::make($data);
    }
}
