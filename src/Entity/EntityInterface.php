<?php

declare(strict_types=1);

namespace WeGetFinancing\SDK\Entity;

use WeGetFinancing\SDK\Exception\EntityValidationException;

/**
 * EntityInterface
 *
 * An entity is the data transport layer of our application.
 * Each entity has to be static makeable, initializable from array, it should be always made by valid data.
 */
interface EntityInterface
{
    /**
     * Factory method: return an entity instantiated with the dependencies.
     * Since this application has not a framework, we avoid using dependency injection in favour of factory methods.
     * Use AbstractEntity::getValidator() to take the correctly instantiated validator.
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param null|array<string, mixed> $data
     * @throws EntityValidationException
     * @return self
     */
    public static function make(array $data = null): self;

    /**
     * Set all the properties from array, where the key is the property name and the value the property value.
     * Execute validation at the end of the set.
     *
     * @param  array<string, mixed> $data
     * @throws EntityValidationException
     * @return self
     */
    public function initFromArray(array $data): self;

    /**
     * Throw an exception if is not valid, return true if it is.
     *
     * @throws EntityValidationException
     */
    public function isValid(): bool;
}
