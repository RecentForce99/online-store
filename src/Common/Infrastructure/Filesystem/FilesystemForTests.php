<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Filesystem;

use App\Common\Application\Filesystem\FilesystemInterface;
use Traversable;

final class FilesystemForTests implements FilesystemInterface
{
    public function copy(string $originFile, string $targetFile, bool $overwriteNewerFiles = false): void
    {
    }

    public function mkdir(iterable|string $dirs, int $mode = 0777): void
    {
    }

    public function exists(iterable|string $files): bool
    {
        return false;
    }

    public function touch(iterable|string $files, ?int $time = null, ?int $atime = null): void
    {
    }

    public function remove(iterable|string $files): void
    {
    }

    public function chmod(iterable|string $files, int $mode, int $umask = 0000, bool $recursive = false): void
    {
    }

    public function chown(iterable|string $files, int|string $user, bool $recursive = false): void
    {
    }

    public function chgrp(iterable|string $files, int|string $group, bool $recursive = false): void
    {
    }

    public function rename(string $origin, string $target, bool $overwrite = false): void
    {
    }

    public function symlink(string $originDir, string $targetDir, bool $copyOnWindows = false): void
    {
    }

    public function hardlink(string $originFile, iterable|string $targetFiles): void
    {
    }

    public function readlink(string $path, bool $canonicalize = false): ?string
    {
        return null;
    }

    public function makePathRelative(string $endPath, string $startPath): string
    {
        return '';
    }

    public function mirror(
        string $originDir,
        string $targetDir,
        ?Traversable $iterator = null,
        array $options = [],
    ): void {
    }

    public function tempnam(string $dir, string $prefix, string $suffix = ''): string
    {
        return '';
    }

    public function dumpFile(string $filename, $content): void
    {
    }

    public function appendToFile(string $filename, $content, bool $lock = false): void
    {
    }

    public function readFile(string $filename): string
    {
        return '';
    }
}
