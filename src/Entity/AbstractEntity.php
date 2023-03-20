<?php

declare(strict_types=1);

namespace WeGetFinancing\SDK\Entity;

use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use WeGetFinancing\SDK\Exception\EntityValidationException;
use TypeError;

abstract class AbstractEntity implements EntityInterface
{
    /**
     * @param ValidatorInterface $validator
     * @param NameConverterInterface $camelCaseToSnakeCase
     * @param null|array<string, mixed> $data
     * @throws EntityValidationException
     */
    public function __construct(
        protected ValidatorInterface $validator,
        protected NameConverterInterface $camelCaseToSnakeCase,
        array $data = null
    ) {
        if (true === is_null($data) || true === empty($data)) {
            return;
        }

        $this->initFromArray($data);
    }

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
                [ 'field' => 'unknown', 'message' => $exception->getMessage() ]
            );
        }

        $this->isValid();
        return $this;
    }

    /**
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
            $messages[] = [
                'field' => $violation->getPropertyPath(),
                'message' => $violation->getMessage(),
            ];
        }

        throw new EntityValidationException(
            EntityValidationException::INVALID_ENTITY_DATA_MESSAGE,
            EntityValidationException::VALIDATION_VIOLATION_INIT_ENTITY_ABSTRACT_CODE,
            null,
            $messages
        );
    }
}
