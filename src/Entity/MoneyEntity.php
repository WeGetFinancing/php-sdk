<?php

declare(strict_types=1);

namespace WeGetFinancing\SDK\Entity;

use WeGetFinancing\SDK\Exception\EntityValidationException;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use TypeError;

class MoneyEntity extends AbstractEntity
{
    public const DECIMALS = 2;

    public const DECIMAL_SEPARATOR = '.';

    #[Assert\Type(type: "numeric", message: "The value {{ value }} is not a valid {{ type }}.")]
    #[Assert\PositiveOrZero(message: "The value should be either positive or zero if allowed.")]
    #[Assert\NotBlank(message: "The value should not be blank.", allowNull: true)]
    #[Assert\NotNull(message: "The value should not be null.")]
    public mixed $value = null;

    #[Assert\Type(type: "string", message: "The value {{ value }} is not a valid {{ type }}.")]
    #[Assert\NotNull(message: "The value of name should not be null.")]
    protected mixed $name = "unknown";

    protected bool $isZeroAllowed = false;

    /**
     * @param ValidatorInterface $validator
     * @param NameConverterInterface $camelCaseToSnakeCase
     * @param null|array<string, mixed> $data
     * @throws EntityValidationException
     * @throws TypeError
     */
    public function __construct(
        ValidatorInterface $validator,
        NameConverterInterface $camelCaseToSnakeCase,
        array $data = null
    ) {
        if (false === is_null($data) && true === array_key_exists('value', $data)) {
            $data['value'] = (true === is_int($data['value']) || true === is_float($data['value']))
                ? (string)$data['value']
                : $data['value'];
        }
        parent::__construct($validator, $camelCaseToSnakeCase, $data);
    }

    /**
     * @throws EntityValidationException
     */
    public function isValid(): bool
    {
        $violations = $this->validator->validate($this);

        $messages = [];

        if (count($violations) > 0) {
            foreach ($violations as $violation) {
                $messages[] = [
                    'field' => true === empty($this->name) ? 'unknown' : $this->name,
                    'message' => "The money entity named " . $this->getFormattedName() . " generated an error, " .
                        $violation->getMessage(),
                ];
            }
        }

        if (
            false === $this->isZeroAllowed &&
            "0.00" === $this->getValue()
        ) {
            $messages[] = [
                'field' => true === empty($this->name) ? 'unknown' : $this->name,
                'message' => "The money entity named " . $this->getFormattedName() . " generated an error, " .
                    EntityValidationException::VALIDATION_VIOLATION_INIT_MONEY_ENTITY_MESSAGE,
            ];
        }

        if (true === empty($messages)) {
            return true;
        }

        throw new EntityValidationException(
            EntityValidationException::INVALID_ENTITY_DATA_MESSAGE,
            EntityValidationException::VALIDATION_VIOLATION_IS_VALID_MONEY_ENTITY_CODE,
            null,
            $messages
        );
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param  null|array<string, mixed> $data
     * @throws EntityValidationException
     * @return MoneyEntity
     */
    public static function make(array $data = null): MoneyEntity
    {
        return new MoneyEntity(
            self::getValidator(),
            new CamelCaseToSnakeCaseNameConverter(),
            $data
        );
    }

    public function getValue(): string
    {
        if (false === is_numeric($this->value)) {
            return "0.00";
        }
        $numberParts = explode(self::DECIMAL_SEPARATOR, $this->value);
        $value = $numberParts[0];
        $value .= self::DECIMAL_SEPARATOR;

        if (false === isset($numberParts[1])) {
            return $value . '00';
        }

        if (1 === strlen($numberParts[1])) {
            return $value . substr($numberParts[1], 0, self::DECIMALS) . '0';
        }

        return $value . substr($numberParts[1], 0, self::DECIMALS);
    }

    private function getFormattedName(): string
    {
        return $this->name;
    }
}
