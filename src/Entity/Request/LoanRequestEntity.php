<?php

declare(strict_types=1);

namespace WeGetFinancing\SDK\Entity\Request;

use WeGetFinancing\SDK\Entity\MoneyEntity;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Validator\Constraints as Assert;
use WeGetFinancing\SDK\Exception\EntityValidationException;

/**
 * Note: I'm suppressing a phpmd warning because I need the long name variable
 * "$merchantTransactionId" to match the request format.
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
class LoanRequestEntity extends AbstractRequestEntity
{
    // @codingStandardsIgnoreStart
    #[Assert\NotBlank(message: "The value of first name should not be blank.", allowNull: true)]
    #[Assert\NotNull(message: "The value of first name should not be null.")]
    #[Assert\Type(type: "string", message: "The value of first name - {{ value }} - is not a valid {{ type }}.")]
    #[Assert\Length(
        min: 2,
        minMessage: "The first name provided in your billing/shipping details seems a bit brief. Would you kindly revisit and update your billing/shipping details, ensuring that the first name contains {{ limit }} or more characters? Once you've made this adjustment, please proceed to reselect WeGetFinancing as your preferred payment method."
    )]
    public mixed $firstName;

    #[Assert\NotBlank(message: "The value of last name should not be blank.", allowNull: true)]
    #[Assert\NotNull(message: "The value of last name should not be null.")]
    #[Assert\Type(type: "string", message: "The value of last name - {{ value }} - is not a valid {{ type }}.")]
    #[Assert\Length(
        min: 2,
        minMessage: "The last name provided in your billing/shipping details seems a bit brief. Would you kindly revisit and update your billing/shipping details, ensuring that the last name contains {{ limit }} or more characters? Once you've made this adjustment, please proceed to reselect WeGetFinancing as your preferred payment method."
    )]
    public mixed $lastName;
    // @codingStandardsIgnoreEnd

    #[Assert\NotNull(message: "The value of shipping amount should not be null.")]
    #[Assert\Type(type: MoneyEntity::class, message: "The value of shipping amount is not a valid MoneyEntity.")]
    public MoneyEntity $shippingAmount;

    #[Assert\NotNull(message: "The value of billing address should not be null.")]
    #[Assert\Type(type: AddressEntity::class, message: "The value of billing address is not a valid AddressEntity.")]
    public AddressEntity $billingAddress;

    #[Assert\NotNull(message: "The value of shipping address should not be null.")]
    #[Assert\Type(type: AddressEntity::class, message: "The value of shipping address is not a valid AddressEntity.")]
    public AddressEntity $shippingAddress;

    #[Assert\NotBlank(message: "The value of email should not be blank.", allowNull: true)]
    #[Assert\NotNull(message: "The value of email should not be null.")]
    #[Assert\Email(message: "The value of email is not a valid email address.")]
    public mixed $email;

    /**
     * @var CartItemEntity[]
     */
    public array $cartItems;

    #[Assert\NotBlank(message: "The value of version should not be blank.", allowNull: true)]
    #[Assert\NotNull(message: "The value of version should not be null.")]
    #[Assert\Type(type: "string", message: "The value of version - {{ value }} - is not a valid {{ type }}.")]
    public mixed $version;

    #[Assert\Type(type: "string", message: "The value of version - {{ value }} - is not a valid {{ type }}.")]
    #[Assert\Regex(
        pattern: "/^[0-9]{10}$/",
        message: "The value of phone have to be 10 digits only.",
        match: true
    )]
    public mixed $phone = null;

    /**
     * @Assert\NotBlank(message = "The value of merchant transaction id should not be blank.")
     */
    #[Assert\NotBlank(message: "The value of merchant transaction id should not be blank.", allowNull: true)]
    #[Assert\NotNull(message: "The value of merchant transaction id should not be null.")]
    #[Assert\Type(
        type: "string",
        message: "The value of merchant transaction id - {{ value }} - is not a valid {{ type }}."
    )]
    public mixed $merchantTransactionId;

    #[Assert\Type(type: "string", message: "The value of success url - {{ value }} - is not a valid {{ type }}.")]
    #[Assert\Url(message: "The value of success url is not a valid URL.")]
    public mixed $successUrl;

    #[Assert\Type(type: "string", message: "The value of failure url - {{ value }} - is not a valid {{ type }}.")]
    #[Assert\Url(message: "The value of failure url is not a valid URL.")]
    public mixed $failureUrl;

    #[Assert\Type(type: "string", message: "The value of postback url - {{ value }} - is not a valid {{ type }}.")]
    #[Assert\Url(message: "The value of postback url is not a valid URL.")]
    public ?string $postbackUrl;

    #[Assert\NotBlank(message: "The value of software name should not be blank.", allowNull: true)]
    #[Assert\Type(type: "string", message: "The value of software name - {{ value }} - is not a valid {{ type }}.")]
    public mixed $softwareName;

    #[Assert\NotBlank(message: "The value of software version should not be blank.", allowNull: true)]
    #[Assert\Type(type: "string", message: "The value of software version - {{ value }} - is not a valid {{ type }}.")]
    public mixed $softwareVersion;

    #[Assert\NotBlank(message: "The value of software plugin version should not be blank.", allowNull: true)]
    #[Assert\Type(
        type: "string",
        message: "The value of software plugin version - {{ value }} - is not a valid {{ type }}."
    )]
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
        $errors = [];

        foreach ($data as $key => $value) {
            try {
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
