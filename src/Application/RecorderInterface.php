<?php

declare(strict_types=1);

namespace JeroenG\Ontology\Application;

use JeroenG\Ontology\Domain\Record;

interface RecorderInterface
{
    public function create(string $class): Record;
}
