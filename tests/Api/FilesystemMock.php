<?php

declare(strict_types=1);

namespace App\Tests\Api;

use App\Common\Application\Filesystem\FilesystemInterface;

final class FilesystemMock implements FilesystemInterface
{
    public function appendToFile(string $filename, $content, bool $lock = false): void
    {
    }

    public function mkdir(iterable|string $dirs, int $mode = 0777): void
    {
    }

    public function exists(iterable|string $files): bool
    {
        return false;
    }
}
