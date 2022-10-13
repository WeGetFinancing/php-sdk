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
    /**
     * @Assert\NotBlank(message = "The value of sku should not be blank.")
     */
    public string $sku;

    /**
     * @Assert\Length(
     *     min = 2,
     *     minMessage = "The value of display name is too short. It should have {{ limit }} characters or more."
     * )
     * @Assert\NotBlank(message = "The value of display name should not be blank.")
     */
    public string $displayName;

    /**
     * @Assert\Type(
     *     type = "WeGetFinancing\SDK\Entity\MoneyEntity",
     *     message = "The value of unit price is not a valid MoneyEntity."
     * )
     */
    public MoneyEntity $unitPrice;

    /**
     * @Assert\Type(
     *     type="integer",
     *     message="The value of quantity is not a valid {{ type }}."
     * )
     * @Assert\Positive(message = "The value of quantity should be positive.")
     */
    public int $quantity;

    /**
     * @Assert\Type(
     *     type = "WeGetFinancing\SDK\Entity\MoneyEntity",
     *     message = "The value of unit tax is not a valid MoneyEntity."
     * )
     */
    public MoneyEntity $unitTax;

    public string $category;

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
        try {
            foreach ($data as $key => $value) {
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
            }
        } catch (TypeError $exception) {
            throw new EntityValidationException(
                EntityValidationException::INVALID_ENTITY_DATA_MESSAGE,
                EntityValidationException::TYPE_ERROR_INIT_ENTITY_CART_ITEM_CODE,
                null,
                [ 'field' => 'unknown', 'message' => $exception->getMessage() ]
            );
        }

        $this->isValid();
        return $this;
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
