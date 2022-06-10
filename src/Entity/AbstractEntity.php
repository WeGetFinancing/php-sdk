<?php

declare(strict_types=1);

namespace WeGetFinancing\PHPSDK\Entity;

use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use WeGetFinancing\PHPSDK\Exception\EntityValidationException;

abstract class AbstractEntity
{
    protected ValidatorInterface $validator;

    protected NameConverterInterface $camelCaseToSnakeCase;

    public function __construct(
        ValidatorInterface $validator,
        NameConverterInterface $camelCaseToSnakeCase
    ) {
        $this->validator = $validator;
        $this->camelCaseToSnakeCase = $camelCaseToSnakeCase;
    }

    /**
     * Factory method: return an entity instantiated with the dependencies.
     * Since this application has not a framework, we avoid using di in favour of factories.
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @return self
     */
    abstract public static function make(): self;

    /**
     * Set all the properties from array, where the key is the property name and the value the property value.
     * Execute validation at the end of the set.
     *
     * @param  array<string, mixed> $data
     * @throws EntityValidationException
     * @return self
     */
    public function initFromArray(array $data): self
    {
        foreach ($data as $key => $value) {
            $propertyName = $this->camelCaseToSnakeCase->denormalize($key);
            $this->{$propertyName} = $value;
        }

        $this->isValid();
        return $this;
    }

    /**
     * Throw an exception if is not valid, return true if it is.
     *
     * @throws EntityValidationException
     */
    public function isValid(): bool
    {
        $violations = $this->validator->validate($this);

        if (0 >= count($violations)) {
            return true;
        }

        $messages = [];
        foreach ($violations as $violation) {
            $messages[] = (string) $violation->getMessage();
        }

        throw new EntityValidationException(
            "Invalid Entity",
            1,
            null,
            $messages
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function getWeGetFinancingRequest(): array
    {
        $requestRaw = get_object_vars($this);

        $request = [];
        foreach ($requestRaw as $nameRaw => $value) {
            $name = $this->camelCaseToSnakeCase->normalize($nameRaw);
            $request[$name] = $value;
        }

        return $request;
    }
}
