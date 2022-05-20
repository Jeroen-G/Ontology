<?php

declare(strict_types=1);

namespace JeroenG\Ontology\Infrastructure\Filesystem;

use JeroenG\Ontology\Application\FilesystemInterface;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem as Flysystem;

final class FilesystemFactory
{
    public static function createWithLocalFlysystem(
        ?string $appRootDirectory,
        string $docsDirectory = 'docs'
    ): FilesystemInterface {
        $adapter = new Local($appRootDirectory);
        $filesystem = new Flysystem($adapter);
        return new Filesystem($docsDirectory, $filesystem);
    }
}
