<?php

declare(strict_types=1);

namespace WeGetFinancing\SDK\Entity\Response;

use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Validator\Constraints as Assert;
use WeGetFinancing\SDK\Entity\AbstractEntity;
use WeGetFinancing\SDK\Exception\EntityValidationException;

class ResponseEntity extends AbstractEntity implements ResponseInterface
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

    /**
     * @var array<string, mixed>
     */
    protected array $data = [];

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @throws EntityValidationException
     */
    public static function make(array $data = null): ResponseEntity
    {
        return new ResponseEntity(
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

    /**
     * @return array<string, mixed>
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array<string, mixed> $data
     * @return $this
     */
    public function setData(array $data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return array<int|string, mixed>
     */
    public function toArray(): array
    {
        return [
            'isSuccess' => $this->isSuccess,
            'code' => $this->code,
            'data' => $this->data,
        ];
    }
}
