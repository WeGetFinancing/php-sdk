<?php

declare(strict_types=1);

namespace WeGetFinancing\SDK\Entity\Response;

use WeGetFinancing\SDK\Entity\AbstractEntity;
use WeGetFinancing\SDK\Entity\MoneyEntity;
use WeGetFinancing\SDK\Exception\EntityValidationException;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Validator\Constraints as Assert;

class LoanSuccessResponseEntity extends AbstractEntity implements ResponseInterface
{
    /**
     * @Assert\NotNull(message = "The value of amount is not a valid MoneyEntity.")
     */
    protected MoneyEntity $amount;

    /**
     * @Assert\Url(message = "The value of href url is not a valid URL.")
     * @Assert\NotBlank(message = "The value of href should not be blank.")
     */
    protected string $href;

    /**
     * @Assert\NotBlank(message = "The value of inv id should not be blank.")
     */
    protected string $invId;

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @throws EntityValidationException
     */
    public static function make(array $data = null): LoanSuccessResponseEntity
    {
        return new LoanSuccessResponseEntity(
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
        if (true === array_key_exists('amount', $data)) {
            $this->amount = MoneyEntity::make([
                "name" => "Amount",
                "value" => $data['amount'],
            ]);
            unset($data['amount']);
        }
        parent::initFromArray($data);
        return $this;
    }

    public function getAmount(): string
    {
        return $this->amount->getValue();
    }

    public function getHref(): string
    {
        return $this->href;
    }

    public function getInvId(): string
    {
        return $this->invId;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'amount' => $this->getAmount(),
            'href' => $this->getHref(),
            'invId' => $this->getInvId(),
        ];
    }
}
