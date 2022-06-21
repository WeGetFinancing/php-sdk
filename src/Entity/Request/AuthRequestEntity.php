<?php

declare(strict_types=1);

namespace App\Entity\Request;

use App\Exception\EntityValidationException;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Validator\Constraints as Assert;

class AuthRequestEntity extends AbstractRequestEntity
{
    public const AUTH_REQUEST_HEADERS = [ 'Authorization' => 'Basic ' ];

    /**
     * @Assert\Length(
     *     min = 2,
     *     minMessage = "The value of username is too short. It should have {{ limit }} characters or more."
     * )
     * @Assert\NotBlank(message = "The value of username should not be blank.")
     */
    public string $username;

    /**
     * @Assert\Length(
     *     min = 2,
     *     minMessage = "The value of password is too short. It should have {{ limit }} characters or more."
     * )
     * @Assert\NotBlank(message = "The value of password should not be blank.")
     */
    public string $password;

    /**
     * @Assert\Length(
     *     min = 2,
     *     minMessage = "The value of merchant id is too short. It should have {{ limit }} characters or more."
     * )
     * @Assert\NotBlank(message = "The value of merchant id should not be blank.")
     */
    public string $merchantId;

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param null|array<string, mixed> $data
     * @return AuthRequestEntity
     *@throws EntityValidationException
     */
    public static function make(array $data = null): AuthRequestEntity
    {
        return new AuthRequestEntity(
            self::getValidator(),
            new CamelCaseToSnakeCaseNameConverter(),
            $data
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function getWeGetFinancingRequest(): array
    {
        $header = self::AUTH_REQUEST_HEADERS;
        $header['Authorization'] = $header['Authorization'] . $this->getBase64Credentials();
        return $header;
    }

    public function getBase64Credentials(): string
    {
        return base64_encode($this->username . ':' . $this->password);
    }
}
