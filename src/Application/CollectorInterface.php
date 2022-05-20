<?php

declare(strict_types=1);

namespace JeroenG\Ontology\Application;

use JeroenG\Ontology\Domain\ClassList;

interface CollectorInterface
{
    public function collect(string $source): ClassList;
}
