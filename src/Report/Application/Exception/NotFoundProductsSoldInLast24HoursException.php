<?php

declare(strict_types=1);

namespace App\Report\Application\Exception;

use Exception;

final class NotFoundProductsSoldInLast24HoursException extends Exception
{
    public static function byReportId(string $reportId): self
    {
        return new self('Не найдено ни одного товара, проданного за последние сутки.');
    }
}
