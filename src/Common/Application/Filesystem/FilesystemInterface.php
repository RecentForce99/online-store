<?php

declare(strict_types=1);

namespace App\Common\Application\Filesystem;

interface FilesystemInterface
{
    public function mkdir(string|iterable $dirs, int $mode = 0777): void;

    public function exists(string|iterable $files): bool;

    public function appendToFile(string $filename, $content, bool $lock = false): void;
}
