<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Trait;

use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

trait FormattedErrorsTrait
{
    private function getFormattedErrors(ConstraintViolationList $errors): array
    {
        $formattedErrors = [];
        /* @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $fieldSlug = $error->getPropertyPath();
            $formattedErrors[$fieldSlug][] = $error->getMessage();
        }

        return $formattedErrors;
    }
}
