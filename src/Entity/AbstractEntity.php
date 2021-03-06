<?php

declare(strict_types=1);

namespace WeGetFinancing\SDK\Entity;

use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use WeGetFinancing\SDK\Exception\EntityValidationException;
use TypeError;

abstract class AbstractEntity
{
    protected ValidatorInterface $validator;

    protected NameConverterInterface $camelCaseToSnakeCase;

    /**
     * @param ValidatorInterface $validator
     * @param NameConverterInterface $camelCaseToSnakeCase
     * @param null|array<string, mixed> $data
     * @throws EntityValidationException
     */
    public function __construct(
        ValidatorInterface $validator,
        NameConverterInterface $camelCaseToSnakeCase,
        array $data = null
    ) {
        $this->validator = $validator;
        $this->camelCaseToSnakeCase = $camelCaseToSnakeCase;

        if (
            true === is_null($data) ||
            true === empty($data)
        ) {
            return;
        }

        $this->initFromArray($data);
    }

    /**
     * Factory method: return an entity instantiated with the dependencies.
     * Since this application has not a framework, we avoid using dependency injection in favour of factoriy methods.
     * Use AbstractEntity::getValidator() to take the correctly instantiated validator.
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param null|array<string, mixed> $data
     * @throws EntityValidationException
     * @return self
     */
    abstract public static function make(array $data = null): self;

    /**
     * Build the validator with annotation mapping and return it.
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public static function getValidator(): ValidatorInterface
    {
        return Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->addDefaultDoctrineAnnotationReader()
            ->getValidator();
    }

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
        try {
            foreach ($data as $key => $value) {
                $propertyName = $this->camelCaseToSnakeCase->denormalize($key);
                $this->{$propertyName} = $value;
            }
        } catch (TypeError $exception) {
            throw new EntityValidationException(
                EntityValidationException::INVALID_ENTITY_DATA_MESSAGE,
                EntityValidationException::TYPE_ERROR_INIT_ENTITY_ABSTRACT_CODE,
                null,
                [ $exception->getMessage() ]
            );
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
            EntityValidationException::INVALID_ENTITY_DATA_MESSAGE,
            EntityValidationException::VALIDATION_VIOLATION_INIT_ENTITY_ABSTRACT_CODE,
            null,
            $messages
        );
    }
}
