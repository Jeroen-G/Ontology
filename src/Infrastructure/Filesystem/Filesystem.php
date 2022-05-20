<?php

declare(strict_types=1);

namespace JeroenG\Ontology\Infrastructure\Filesystem;

use JeroenG\Ontology\Application\FilesystemInterface;
use JeroenG\Ontology\Domain\Filesystem\Directory;
use League\Flysystem\FilesystemInterface as FlysystemInterface;

final class Filesystem implements FilesystemInterface
{
    public function __construct(
        private string $root,
        private FlysystemInterface $flysystem,
    ) {
    }

    public function save(Directory $directory): void
    {
        foreach ($directory->getFiles() as $file) {
            $path = $this->root.'/'.$directory->getName().'/'.$file->getName();

            if ($this->flysystem->has($path)) {
                $this->flysystem->delete($path);
            }

            $this->flysystem->write($path, $file->getContent());
        }
    }
}
