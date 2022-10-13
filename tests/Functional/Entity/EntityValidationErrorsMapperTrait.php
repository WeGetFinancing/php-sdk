<?php

declare(strict_types=1);

namespace Functional\Entity;

use Stringable;
use WeGetFinancing\SDK\Exception\EntityValidationException;

trait EntityValidationErrorsMapperTrait
{
    /**
     * @param EntityValidationException $exception
     * @return array<int|string, string|mixed>
     */
    public function getViolationMessages(EntityValidationException $exception): array
    {
        $violations = $exception->getViolations();
        return array_map(
            function (array $violation) {
                return $violation['message'];
            },
            $violations
        );
    }
}
