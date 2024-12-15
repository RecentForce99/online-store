<?php

declare(strict_types=1);

namespace App\Report\Application\SoldProduct;

use Symfony\Component\Uid\UuidV4;

final class SoldProductReportCommand
{
    public readonly string $reportId;

    public function __construct()
    {
        $this->reportId = UuidV4::v4()->toString();
    }
}
