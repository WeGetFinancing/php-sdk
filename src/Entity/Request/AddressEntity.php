<?php

declare(strict_types=1);

namespace WeGetFinancing\SDK\Entity\Request;

use WeGetFinancing\SDK\Exception\EntityValidationException;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Validator\Constraints as Assert;
use WeGetFinancing\SDK\Validator\Constraints as WeGetFinancingAssert;

class AddressEntity extends AbstractRequestEntity
{
    #[Assert\NotBlank(message: "The value of street1 should not be blank.", allowNull: true)]
    #[Assert\NotNull(message: "The value of street1 should not be null.")]
    #[Assert\Length(
        min: 2,
        minMessage: "The value of street1 is too short. It should have {{ limit }} characters or more."
    )]
    #[Assert\Type(type: "string", message: "The value of street1 - {{ value }} - is not a valid {{ type }}.")]
    public mixed $street1;

    #[Assert\NotBlank(message: "The value of city should not be blank.", allowNull: true)]
    #[Assert\NotNull(message: "The value of city should not be null.")]
    #[Assert\Length(
        min: 2,
        minMessage: "The value of city is too short. It should have {{ limit }} characters or more."
    )]
    #[Assert\Type(type: "string", message: "The value of city - {{ value }} - is not a valid {{ type }}.")]
    public mixed $city;

    #[Assert\NotBlank(message: "The value of state should not be blank.", allowNull: true)]
    #[Assert\NotNull(message: "The value of state should not be null.")]
    #[Assert\Length(
        min: 2,
        max: 2,
        exactMessage: "The value of state should have exactly 2 characters."
    )]
    #[Assert\Type(type: "string", message: "The value of state - {{ value }} - is not a valid {{ type }}.")]
    public mixed $state;

    #[Assert\NotBlank(message: "The value of zipcode should not be blank.", allowNull: true)]
    #[Assert\NotNull(message: "The value of zipcode should not be null.")]
    #[WeGetFinancingAssert\IsAValidUSZipCode(
        message: "The value of zipcode should contain only 5 numbers optionally followed by a dash and 4 numbers."
    )]
    #[Assert\Type(type: "string", message: "The value of zipcode - {{ value }} - is not a valid {{ type }}.")]
    public mixed $zipcode;

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param null|array<string, mixed> $data
     * @throws EntityValidationException
     * @return AddressEntity
     */
    public static function make(array $data = null): AddressEntity
    {
        return new AddressEntity(
            parent::getValidator(),
            new CamelCaseToSnakeCaseNameConverter(),
            $data
        );
    }
}
