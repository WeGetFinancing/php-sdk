<?php

declare(strict_types=1);

//namespace App\Entity;
//
//use Symfony\Component\Validator\Validation;
//use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
//use Symfony\Component\Validator\Constraints as Assert;
//use App\Exception\EntityValidationException;
//
///**
// * @Note: I'm suppressing a phpmd warning because I need the long name variable
// * "$merchantTransactionId" to match the request format.
// *
// * @SuppressWarnings(PHPMD.LongVariable)
// */
//class LoanRequestEntity extends AbstractEntity
//{
//    /**
//     * @Assert\Length(
//     *     min = 2,
//     *     minMessage = "The value of first name is too short. It should have {{ limit }} characters or more."
//     * )
//     * @Assert\NotBlank(message = "The value of first name should not be blank.")
//     */
//    public string $firstName;
//
//    /**
//     * @Assert\Length(
//     *     min = 2,
//     *     minMessage = "The value of last name is too short. It should have {{ limit }} characters or more."
//     * )
//     * @Assert\NotBlank(message = "The value of last name should not be blank.")
//     */
//    public string $lastName;
//
//    /**
//     * @Assert\PositiveOrZero()
//     * @Assert\NotBlank()
//     * @Assert\NotNull()
//     */
//    public float $shippingAmount;
//
//    public AddressEntity $billingAddress;
//
//    public AddressEntity $shippingAddress;
//
//    /**
//     * @Assert\Email()
//     * @Assert\NotBlank()
//     * @Assert\NotNull()
//     */
//    public string $email;
//
//    /**
//     * @var AbstractEntity[]
//     */
//    public array $cartItems;
//
//    public string $phone;
//
//    public string $merchantTransactionId;
//
//    public string $successUrl;
//
//    public string $failureUrl;
//
//    public string $postbackUrl;
//
//    /**
//     * @SuppressWarnings(PHPMD.StaticAccess)
//     */
//    public static function make(): LoanRequestEntity
//    {
//        return new LoanRequestEntity(
//            Validation::createValidator(),
//            new CamelCaseToSnakeCaseNameConverter()
//        );
//    }
//
//    /**
//     * @param  array<string, mixed> $data
//     * @throws EntityValidationException
//     * @return self
//     */
//    public function initFromArray(array $data): self
//    {
//        $denormalizedData = [];
//        foreach ($data as $key => $value) {
//            $denormalizedData[$this->camelCaseToSnakeCase->denormalize($key)] = $value;
//        }
//
//        $denormalizedData = $this->setAddressFromInitArray('billingAddress', $denormalizedData);
//        $denormalizedData = $this->setAddressFromInitArray('shippingAddress', $denormalizedData);
//        $denormalizedData = $this->setCartItemsFromInitArray($denormalizedData);
//        parent::initFromArray($denormalizedData);
//
//        return $this;
//    }
//
//    /**
//     * @param  string $key
//     * @param  array<string, mixed> $data
//     * @throws EntityValidationException
//     * @return array<string, mixed>
//     */
//    private function setAddressFromInitArray(string $key, array $data): array
//    {
//        if (
//            false === array_key_exists($key, $data) ||
//            true === is_null($data[$key])
//        ) {
//            throw new EntityValidationException(
//                'LoanRequestEntity::setAddressFromInitArray error',
//                1,
//                null,
//                [ $key . ' is not set or null.' ]
//            );
//        }
//
//        $this->{$key} = AddressEntity::make()->initFromArray($data[$key]);
//
//        unset($data[$key]);
//
//        return $data;
//    }
//
//    /**
//     * @param  array<string, mixed> $data
//     * @throws EntityValidationException
//     * @return array<string, mixed>
//     */
//    private function setCartItemsFromInitArray(array $data): array
//    {
//        if (
//            false === array_key_exists('cartItems', $data) ||
//            true === empty($data['cartItems'])
//        ) {
//            throw new EntityValidationException(
//                'LoanRequestEntity::setCartItemsFromInitArray error',
//                2,
//                null,
//                [ 'cartItems is not set or null.' ]
//            );
//        }
//
//        $this->cartItems = [];
//        foreach ($data['cartItems'] as $cartItemData) {
//            $this->cartItems[] = CartItemEntity::make()->initFromArray($cartItemData);
//        }
//        unset($data['cartItems']);
//
//        return $data;
//    }
//}
