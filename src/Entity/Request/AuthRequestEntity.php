<?php

declare(strict_types=1);

namespace WeGetFinancingSDK\Entity\Request;

use WeGetFinancingSDK\Exception\EntityValidationException;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Validator\Constraints as Assert;

class AuthRequestEntity extends AbstractRequestEntity
{
    public const AUTH_REQUEST_HEADERS = [
        'Content-Type' => 'application/json',
        'Accept' =>  'application/json',
        'Authorization' => 'Basic ',
    ];

    public const LOAN_REQUEST_PATH = '/merchant/%MERCHANT_ID%/requests';

    public const MERCHANT_ID_REPLACE = '%MERCHANT_ID%';

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
     * @Assert\Url(
     *     protocols = { "https" },
     *     message = "The value of url is not a valid URL."
     * )
     * @Assert\NotBlank(message = "The value of url should not be blank.")
     */
    public string $url;

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param null|array<string, mixed> $data
     * @throws EntityValidationException
     * @return AuthRequestEntity
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

    public function getRequestNewLoanUrl(): string
    {
        return $this->url . str_replace(
            self::MERCHANT_ID_REPLACE,
            $this->merchantId,
            self::LOAN_REQUEST_PATH
        );
    }
}
