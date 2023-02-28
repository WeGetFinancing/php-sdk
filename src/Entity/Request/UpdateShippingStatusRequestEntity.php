<?php

namespace WeGetFinancing\SDK\Entity\Request;

use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Validator\Constraints as Assert;
use WeGetFinancing\SDK\Exception\EntityValidationException;

class UpdateShippingStatusRequestEntity extends AbstractRequestEntity
{
    public const STATUS_SHIPPED = 'shipped';
    public const STATUS_STOCKED = 'stocked';
    public const STATUS_SHORTAGE = 'shortage';
    public const STATUS_DELIVERED = 'delivered';
    public const VALID_STATUSES = [
        self::STATUS_SHIPPED,
        self::STATUS_STOCKED,
        self::STATUS_SHORTAGE,
        self::STATUS_DELIVERED
    ];

    /**
     * @Assert\NotNull(message = "The value of shipment status should not be null.")
     * @Assert\Choice(
     *      choices = UpdateShippingStatusRequestEntity::VALID_STATUSES,
     *      message = "Choose a valid shipment status."
     * )
     */
    public null|string $shippingStatus;

    /**
     * @Assert\NotNull(message = "The value of tracking id status should not be null.")
     */
    public null|string $trackingId;

    /**
     * @Assert\NotNull(message = "The value of tracking company should not be null.")
     */
    public null|string $trackingCompany;

    /**
     * @Assert\NotNull(message = "The value of delivery date should not be null.")
     * @Assert\Date(message = "The value of delivery date is not a valid Date with format YYYY-MM-DD.")
     */
    public null|string $deliveryDate;

    /**
     * @Assert\NotNull(message = "The value of invId should not be null.")
     */
    protected null|string $invId;

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param null|array<string, mixed> $data
     * @throws EntityValidationException
     * @return UpdateShippingStatusRequestEntity
     */
    public static function make(array $data = null): UpdateShippingStatusRequestEntity
    {
        return new UpdateShippingStatusRequestEntity(
            self::getValidator(),
            new CamelCaseToSnakeCaseNameConverter(),
            $data
        );
    }

    public function getInvId(): null|string
    {
        return $this->invId;
    }
}
