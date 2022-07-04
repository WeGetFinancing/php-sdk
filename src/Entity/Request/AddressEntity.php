<?php

declare(strict_types=1);

namespace WeGetFinancing\SDK\Entity\Request;

use WeGetFinancing\SDK\Exception\EntityValidationException;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Validator\Constraints as Assert;
use WeGetFinancing\SDK\Validator\Constraints as WeGetFinancingAssert;

class AddressEntity extends AbstractRequestEntity
{
    /**
     * @Assert\Length(
     *     min = 2,
     *     minMessage = "The value of street1 is too short. It should have {{ limit }} characters or more."
     * )
     * @Assert\NotBlank(message = "The value of street1 should not be blank.")
     */
    public string $street1;

    /**
     * @Assert\Length(
     *     min = 2,
     *     minMessage = "The value of city is too short. It should have {{ limit }} characters or more."
     * )
     * @Assert\NotBlank(message = "The value of city should not be blank.")
     */
    public string $city;

    /**
     * @Assert\Length(
     *     min = 2,
     *     max = 2,
     *     exactMessage="The value of state should have exactly 2 characters."
     * )
     * @Assert\NotBlank(message = "The value of state should not be blank.")
     */
    public string $state;

    /**
     * @Assert\NotBlank(message = "The value of zipcode should not be blank.")
     * @WeGetFinancingAssert\IsAValidUSZipCode(
     *     message = "The value of zipcode should contain only 5 numbers optionally followed by a dash and 4 numbers."
     * )
     */
    public string $zipcode;

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
