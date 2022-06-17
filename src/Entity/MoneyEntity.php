<?php

declare(strict_types=1);

namespace App\Entity;

use App\Exception\EntityValidationException;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use TypeError;

class MoneyEntity extends AbstractEntity
{
    public const DECIMALS = 2;

    public const DECIMAL_SEPARATOR = '.';

    /**
     * @Assert\Type(
     *     type = "numeric",
     *     message = "The money value is not a valid {{ type }}."
     * )
     * @Assert\PositiveOrZero(message = "The money value should be either positive or zero.")
     * @Assert\NotBlank(message = "The money value should not be blank.")
     */
    public string $value;


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
        if (
            (false === is_null($data) && true === array_key_exists('value', $data)) &&
            (true === is_int($data['value']) || true === is_float($data['value']))
        ) {
            $data['value'] = (string)$data['value'];
        }

        parent::__construct($validator, $camelCaseToSnakeCase, $data);
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param  null|array<string, mixed> $data
     * @return MoneyEntity
     * @throws EntityValidationException
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

    /**
     * @return array<string, string>
     */
    public function getWeGetFinancingRequest(): array
    {
        return [
            'value' => $this->getValue()
        ];
    }
}
