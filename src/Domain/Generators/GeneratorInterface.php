<?php

declare(strict_types=1);

namespace JeroenG\Ontology\Domain\Generators;

use JeroenG\Ontology\Domain\ClassList;
use JeroenG\Ontology\Domain\Filesystem\Directory;

interface GeneratorInterface
{
    public function supports(string $type): bool;

    public function generate(ClassList $list): Directory;
}
