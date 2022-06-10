<?php

declare(strict_types=1);

namespace WeGetFinancing\PHPSDK\Entity;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Validator\Constraints as Assert;
use WeGetFinancing\PHPSDK\Validator as WGFAssert;

class AddressEntity extends AbstractEntity
{
    /**
     * @Assert\Length(min = 3)
     * @Assert\NotBlank()
     * @Assert\NotNull()
     */
    public string $street1;

    /**
     * @Assert\Length(min = 2)
     * @Assert\NotBlank()
     * @Assert\NotNull()
     */
    public string $city;

    /**
     * @Assert\Length(min = 2)
     * @Assert\NotBlank()
     * @Assert\NotNull()
     */
    public string $state;

    /**
     * @Assert\Length(min = 5)
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @WGFAssert\IsAValidUSZipCode()
     */
    public string $zipcode;

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public static function make(): AddressEntity
    {
        return new AddressEntity(
            Validation::createValidator(),
            new CamelCaseToSnakeCaseNameConverter()
        );
    }
}
