<?php

declare(strict_types=1);

namespace WeGetFinancing\SDK\Entity\Request;

use WeGetFinancing\SDK\Entity\MoneyEntity;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Validator\Constraints as Assert;
use WeGetFinancing\SDK\Exception\EntityValidationException;
use TypeError;

/**
 * Note: I'm suppressing a phpmd warning because I need the long name variable
 * "$merchantTransactionId" to match the request format.
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
class LoanRequestEntity extends AbstractRequestEntity
{
    /**
     * @Assert\Length(
     *     min = 2,
     *     minMessage = "The value of first name is too short. It should have {{ limit }} characters or more."
     * )
     * @Assert\NotBlank(message = "The value of first name should not be blank.")
     */
    public string $firstName;

    /**
     * @Assert\Length(
     *     min = 2,
     *     minMessage = "The value of last name is too short. It should have {{ limit }} characters or more."
     * )
     * @Assert\NotBlank(message = "The value of last name should not be blank.")
     */
    public string $lastName;

    /**
     * @Assert\Type(
     *     type = "WeGetFinancing\SDK\Entity\MoneyEntity",
     *     message = "The value of shipping amount is not a valid MoneyEntity."
     * )
     */
    public MoneyEntity $shippingAmount;

    /**
     * @Assert\Type(
     *     type = "WeGetFinancing\SDK\Entity\Request\AddressEntity",
     *     message = "The value of billing address is not a valid AddressEntity."
     * )
     */
    public AddressEntity $billingAddress;

    /**
     * @Assert\Type(
     *     type = "WeGetFinancing\SDK\Entity\Request\AddressEntity",
     *     message = "The value of shipping address is not a valid AddressEntity."
     * )
     */
    public AddressEntity $shippingAddress;

    /**
     * @Assert\Email(message = "The value of email is not a valid email address.")
     * @Assert\NotBlank(message = "The value of email should not be blank.")
     */
    public string $email;

    /**
     * @var CartItemEntity[]
     */
    public array $cartItems;

    /**
     * @Assert\NotBlank(message = "The value of version should not be blank.")
     */
    public string $version;

    /**
     * @Assert\Regex(
     *     pattern = "/^[0-9]{10}$/",
     *     match = true,
     *     message = "The value of phone have to be 10 digits only."
     * )
     */
    public ?string $phone = null;

    /**
     * @Assert\NotBlank(message = "The value of merchant transaction id should not be blank.")
     */
    public ?string $merchantTransactionId;

    /**
     * @Assert\Url(message = "The value of success url is not a valid URL.")
     */
    public ?string $successUrl;

    /**
     * @Assert\Url(message = "The value of failure url is not a valid URL.")
     */
    public ?string $failureUrl;

    /**
     * @Assert\Url(message = "The value of postback url is not a valid URL.")
     */
    public ?string $postbackUrl;

    /**
     * @Assert\NotBlank(message = "The value of software name should not be blank.")
     */
    public ?string $softwareName;

    /**
     * @Assert\NotBlank(message = "The value of software version should not be blank.")
     */
    public ?string $softwareVersion;

    /**
     * @Assert\NotBlank(message = "The value of software plugin version should not be blank.")
     */
    public ?string $softwarePluginVersion;

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param null|array<string, mixed> $data
     * @throws EntityValidationException
     * @return LoanRequestEntity
     */
    public static function make(array $data = null): LoanRequestEntity
    {
        return new LoanRequestEntity(
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

                if ("shippingAmount" === $denormalizedProp) {
                    $this->shippingAmount = MoneyEntity::make([
                        'value' => $value,
                        'name' => 'shipping amount',
                        'isZeroAllowed' => true,
                    ]);
                    continue;
                }
                if ("billingAddress" === $denormalizedProp) {
                    $this->billingAddress = AddressEntity::make($value);
                    continue;
                }
                if ("shippingAddress" === $denormalizedProp) {
                    $this->shippingAddress = AddressEntity::make($value);
                    continue;
                }
                if ("cartItems" === $denormalizedProp) {
                    $this->setCartItemFromArray($value);
                    continue;
                }

                $this->{$denormalizedProp} = $value;
            }
        } catch (TypeError $exception) {
            throw new EntityValidationException(
                EntityValidationException::INVALID_ENTITY_DATA_MESSAGE,
                EntityValidationException::TYPE_ERROR_INIT_ENTITY_LOAN_REQUEST_CODE,
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
        $request['shipping_amount'] = $this->shippingAmount->getValue();
        $request['billing_address'] = $this->billingAddress->getWeGetFinancingRequest();
        $request['shipping_address'] = $this->shippingAddress->getWeGetFinancingRequest();
        $request['cart_items'] = array_reduce(
            $this->cartItems,
            function (array $carry, CartItemEntity $cartItem) {
                $carry[] = $cartItem->getWeGetFinancingRequest();
                return $carry;
            },
            []
        );
        return $request;
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param  array<string, mixed> $cartItemsRaw
     * @throws EntityValidationException
     * @return $this
     */
    protected function setCartItemFromArray(array $cartItemsRaw): self
    {
        $this->cartItems = [];
        foreach ($cartItemsRaw as $cartItemRaw) {
            $this->cartItems[] = CartItemEntity::make($cartItemRaw);
        }
        return $this;
    }
}
