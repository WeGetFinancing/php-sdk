<?php

declare(strict_types=1);

namespace WeGetFinancing\SDK\Entity;

use WeGetFinancing\SDK\Exception\EntityValidationException;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Validator\Constraints as Assert;

class AuthEntity extends AbstractEntity
{
    /**
     * @Assert\Length(
     *     min = 2,
     *     minMessage = "The value of username is too short. It should have {{ limit }} characters or more."
     * )
     * @Assert\NotBlank(message = "The value of username should not be blank.")
     */
    protected string $username;

    /**
     * @Assert\Length(
     *     min = 2,
     *     minMessage = "The value of password is too short. It should have {{ limit }} characters or more."
     * )
     * @Assert\NotBlank(message = "The value of password should not be blank.")
     */
    protected string $password;

    /**
     * @Assert\Length(
     *     min = 2,
     *     minMessage = "The value of merchant id is too short. It should have {{ limit }} characters or more."
     * )
     * @Assert\NotBlank(message = "The value of merchant id should not be blank.")
     */
    protected string $merchantId;

    protected bool $prod = false;

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param null|array<string, mixed> $data
     * @throws EntityValidationException
     * @return AuthEntity
     */
    public static function make(array $data = null): AuthEntity
    {
        return new AuthEntity(
            self::getValidator(),
            new CamelCaseToSnakeCaseNameConverter(),
            $data
        );
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getMerchantId(): string
    {
        return $this->merchantId;
    }

    public function isProd(): bool
    {
        return $this->prod;
    }
}
