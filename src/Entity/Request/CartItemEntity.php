<?php

declare(strict_types=1);

namespace WeGetFinancing\SDK\Entity\Request;

use WeGetFinancing\SDK\Entity\MoneyEntity;
use WeGetFinancing\SDK\Exception\EntityValidationException;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Validator\Constraints as Assert;
use TypeError;

class CartItemEntity extends AbstractRequestEntity
{
    #[Assert\NotBlank(message: "The value of sku should not be blank.", allowNull: true)]
    #[Assert\NotNull(message: "The value of sku should not be null.")]
    #[Assert\Type(type: "string", message: "The value of sku - {{ value }} - is not a valid {{ type }}.")]
    public mixed $sku;

    #[Assert\NotBlank(message: "The value of display name should not be blank.", allowNull: true)]
    #[Assert\NotNull(message: "The value of display name should not be null.")]
    #[Assert\Type(type: "string", message: "The value of display name - {{ value }} - is not a valid {{ type }}.")]
    #[Assert\Length(
        min: 2,
        minMessage: "The value of display name is too short. It should have {{ limit }} characters or more."
    )]
    public mixed $displayName;

    #[Assert\NotNull(message: "The value of unit price should not be null.")]
    #[Assert\Type(type: MoneyEntity::class, message: "The value of unit price is not a valid MoneyEntity.")]
    public MoneyEntity $unitPrice;

    #[Assert\Positive(message: "The value of quantity should be positive.")]
    #[Assert\Type(type: "integer", message: "The value of quantity - {{ value }} - is not a valid {{ type }}.")]
    public mixed $quantity;

    #[Assert\NotNull(message: "The value of unit tax should not be null.")]
    #[Assert\Type(type: MoneyEntity::class, message: "The value of unit tax is not a valid MoneyEntity.")]
    public MoneyEntity $unitTax;

    #[Assert\Type(type: "string", message: "The value of category - {{ value }} - is not a valid {{ type }}.")]
    public mixed $category;

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param null|array<string, mixed> $data
     * @throws EntityValidationException
     * @return CartItemEntity
     */
    public static function make(array $data = null): CartItemEntity
    {
        return new CartItemEntity(
            self::getValidator(),
            new CamelCaseToSnakeCaseNameConverter(),
            $data
        );
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param  array<string, mixed> $data
     * @throws EntityValidationException
     * @return self
     */
    public function initFromArray(array $data): self
    {
        $errors = [];
        foreach ($data as $key => $value) {
            try {
                $denormalizedProp = $this->camelCaseToSnakeCase->denormalize($key);

                if ("unitPrice" === $denormalizedProp) {
                    $this->unitPrice = MoneyEntity::make([
                        "name" => "Unit Price",
                        "value" => $value,
                    ]);
                    continue;
                }
                if ("unitTax" === $denormalizedProp) {
                    $this->unitTax = MoneyEntity::make([
                        "name" => "Unit Tax",
                        "isZeroAllowed" => true,
                        "value" => $value,
                    ]);
                    continue;
                }

                $this->{$denormalizedProp} = $value;
            } catch (EntityValidationException $exception) {
                $errors = array_merge($errors, $exception->getViolations());
            }
        }

        return $this->compositeIsValid($errors);
    }

    /**
     * @return array<string, mixed>
     */
    public function getWeGetFinancingRequest(): array
    {
        $request = parent::getWeGetFinancingRequest();
        $request['unit_price'] = $this->unitPrice->getValue();
        $request['unit_tax'] = $this->unitTax->getValue();
        return $request;
    }
}
