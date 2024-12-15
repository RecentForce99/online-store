<?php

declare(strict_types=1);

namespace App\Common\Application\Filesystem;

use Traversable;

interface FilesystemInterface
{
    public function copy(string $originFile, string $targetFile, bool $overwriteNewerFiles = false): void;

    public function mkdir(string|iterable $dirs, int $mode = 0777): void;

    public function exists(string|iterable $files): bool;

    public function touch(string|iterable $files, ?int $time = null, ?int $atime = null): void;

    public function remove(string|iterable $files): void;

    public function chmod(string|iterable $files, int $mode, int $umask = 0000, bool $recursive = false): void;

    public function chown(string|iterable $files, string|int $user, bool $recursive = false): void;

    public function chgrp(string|iterable $files, string|int $group, bool $recursive = false): void;

    public function rename(string $origin, string $target, bool $overwrite = false): void;

    public function symlink(string $originDir, string $targetDir, bool $copyOnWindows = false): void;

    public function hardlink(string $originFile, string|iterable $targetFiles): void;

    public function readlink(string $path, bool $canonicalize = false): ?string;

    public function makePathRelative(string $endPath, string $startPath): string;

    public function mirror(
        string $originDir,
        string $targetDir,
        ?Traversable $iterator = null,
        array $options = [],
    ): void;

    public function tempnam(string $dir, string $prefix, string $suffix = ''): string;

    public function dumpFile(string $filename, $content): void;

    public function appendToFile(string $filename, $content, bool $lock = false): void;

    public function readFile(string $filename): string;
}
