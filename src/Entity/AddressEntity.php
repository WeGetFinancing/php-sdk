<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as WeGetFinancingAssert;

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
     * @Assert\Length(min = 2, max = 2)
     * @Assert\NotBlank()
     * @Assert\NotNull()
     */
    public string $state;

    /**
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @WeGetFinancingAssert\IsAValidUSZipCode()
     */
    public string $zipcode;

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public static function make(): AddressEntity
    {
        return new AddressEntity(
            parent::getValidator(),
            new CamelCaseToSnakeCaseNameConverter()
        );
    }
}
