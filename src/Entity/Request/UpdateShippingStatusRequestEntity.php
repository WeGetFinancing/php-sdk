<?php

declare(strict_types=1);

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
        self::STATUS_DELIVERED,
    ];

    #[Assert\NotBlank(message: "The value of shipment status should not be blank.", allowNull: true)]
    #[Assert\NotNull(message: "The value of shipment status should not be null.")]
    #[Assert\Type(type: "string", message: "The value of shipment status - {{ value }} - is not a valid {{ type }}.")]
    #[Assert\Choice(
        choices: UpdateShippingStatusRequestEntity::VALID_STATUSES,
        message: "Choose a valid shipment status."
    )]
    public mixed $shippingStatus;

    #[Assert\NotNull(message: "The value of tracking id should not be null.")]
    #[Assert\Type(type: "string", message: "The value of tracking id - {{ value }} - is not a valid {{ type }}.")]
    public mixed $trackingId;

    #[Assert\NotNull(message: "The value of tracking company should not be null.")]
    #[Assert\Type(type: "string", message: "The value of tracking company - {{ value }} - is not a valid {{ type }}.")]
    public mixed $trackingCompany;

    #[Assert\NotNull(message: "The value of delivery date should not be null.")]
    #[Assert\Type(type: "string", message: "The value of delivery date - {{ value }} - is not a valid {{ type }}.")]
    #[Assert\Date(message: "The value of delivery date is not a valid Date with format YYYY-MM-DD.")]
    public mixed $deliveryDate;

    #[Assert\NotNull(message: "The value of inv id should not be null.")]
    #[Assert\Type(type: "string", message: "The value of inv id - {{ value }} - is not a valid {{ type }}.")]
    protected mixed $invId;

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

    public function getInvId(): ?string
    {
        return $this->invId;
    }
}
