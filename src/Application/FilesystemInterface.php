<?php

declare(strict_types=1);

namespace JeroenG\Ontology\Application;

use JeroenG\Ontology\Domain\Filesystem\Directory;

interface FilesystemInterface
{
    public function save(Directory $directory): void;
}
