<?php

declare(strict_types=1);

namespace WeGetFinancing\PHPSDK\Entity;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Validator\Constraints as Assert;

class CartItemEntity extends AbstractEntity
{
    /**
     * @Assert\Length(min = 1)
     * @Assert\NotBlank()
     * @Assert\NotNull()
     */
    public string $sku;

    /**
     * @Assert\Length(min = 2)
     * @Assert\NotBlank()
     * @Assert\NotNull()
     */
    public string $displayName;

    /**
     * @Assert\Positive()
     * @Assert\NotBlank()
     * @Assert\NotNull()
     */
    public float $unitPrice;

    /**
     * @Assert\Positive()
     * @Assert\NotBlank()
     * @Assert\NotNull()
     */
    public int $quantity;

    /**
     * @Assert\Positive()
     * @Assert\NotBlank()
     * @Assert\NotNull()
     */
    public float $unitTax;

    public string $category;

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public static function make(): CartItemEntity
    {
        return new CartItemEntity(
            Validation::createValidator(),
            new CamelCaseToSnakeCaseNameConverter()
        );
    }
}
