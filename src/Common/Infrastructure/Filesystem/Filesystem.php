<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Filesystem;

use App\Common\Application\Filesystem\FilesystemInterface;

final class Filesystem extends \Symfony\Component\Filesystem\Filesystem implements FilesystemInterface
{
}
