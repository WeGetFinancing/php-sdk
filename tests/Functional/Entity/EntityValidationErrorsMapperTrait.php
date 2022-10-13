<?php

namespace Functional\Entity;

use WeGetFinancing\SDK\Exception\EntityValidationException;

trait EntityValidationErrorsMapperTrait
{
    public function getViolationMessages(EntityValidationException $exception): array
    {
        $violations = $exception->getViolations();
        return (true === array_key_exists('field', $violations) &&
            true === array_key_exists('message', $violations))
            ? [$violations['message']]
            : array_map(
                function (array $violation) {
                    return $violation['message'];
                },
                $violations
            );
    }
}
