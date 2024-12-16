<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Trait;

use App\Common\Infrastructure\Exception\ConstraintViolationException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;

trait FormatConstraintViolationTrait
{
    /**
     * @throws ConstraintViolationException
     */
    protected function throwFirstFormattedViolationExceptionIfThereIsOne(ConstraintViolationListInterface $constraintViolations): void
    {
        $firstConstraintViolation = $this->getFirstFormattedViolation($constraintViolations);
        if (false === $firstConstraintViolation) {
            return;
        }

        throw ConstraintViolationException::byMessage($firstConstraintViolation);
    }

    protected function getFirstFormattedViolation(ConstraintViolationListInterface $constraintViolations): false|string
    {
        return current($this->formatConstraintViolationException($constraintViolations));
    }

    protected function formatConstraintViolationException(ConstraintViolationListInterface $constraintViolations): array
    {
        if (count($constraintViolations) > 0) {
            return $this->getFormattedErrors($constraintViolations);
        }

        return [];
    }

    private function getFormattedErrors(ConstraintViolationList $errors): array
    {
        $formattedErrors = [];
        /* @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $formattedErrors[] = $error->getMessage();
        }

        return $formattedErrors;
    }
}
