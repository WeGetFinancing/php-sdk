<?php

declare(strict_types=1);

namespace WeGetFinancing\SDK\Entity\Response;

use WeGetFinancing\SDK\Entity\AbstractEntity;
use WeGetFinancing\SDK\Exception\EntityValidationException;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Validator\Constraints as Assert;

class LoanErrorResponseEntity extends AbstractEntity
{
    /**
     * @Assert\NotBlank(message = "The value of error should not be blank.")
     */
    protected string $error;

    /**
     * @Assert\NotBlank(message = "The value of message should not be blank.")
     */
    protected string $message;

    /**
     * @Assert\NotBlank(message = "The value of type should not be blank.")
     */
    protected string $type;

    /**
     * @Assert\NotBlank(message = "The value of stamp should not be blank.")
     */
    protected string $stamp;

    protected ?string $debug = null;

    /**
     * @var null|array<string, string>
     */
    protected ?array $subjects = null;

    /**
     * @var null|string[]
     */
    protected ?array $reasons = null;

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @param $data null|array<string, mixed>
     * @throws EntityValidationException
     */
    public static function make(array $data = null): LoanErrorResponseEntity
    {
        return new LoanErrorResponseEntity(
            self::getValidator(),
            new CamelCaseToSnakeCaseNameConverter(),
            $data
        );
    }

    public function getError(): string
    {
        return $this->error;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getStamp(): string
    {
        return $this->stamp;
    }

    public function getDebug(): ?string
    {
        return $this->debug;
    }

    /**
     * @return  null|string[]
     */
    public function getSubjects(): ?array
    {
        return $this->subjects;
    }

    /**
     * @return  null|array<string, string>
     */
    public function getReasons(): ?array
    {
        return $this->reasons;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'error' => $this->getError(),
            'message' => $this->getMessage(),
            'type' => $this->getType(),
            'stamp' => $this->getStamp(),
            'debug' => $this->getDebug(),
            'subjects' => $this->getSubjects(),
            'reasons' => $this->getReasons(),
        ];
    }
}
