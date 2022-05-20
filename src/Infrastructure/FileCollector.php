<?php

declare(strict_types=1);

namespace JeroenG\Ontology\Infrastructure;

use Ergebnis\Classy\Constructs;
use JeroenG\Ontology\Application\CollectorInterface;
use JeroenG\Ontology\Application\RecorderInterface;
use JeroenG\Ontology\Domain\ClassList;

final class FileCollector implements CollectorInterface
{
    public function __construct(
        private RecorderInterface $recorder,
    ){
    }

    public function collect(string $source): ClassList
    {
        $constructs = Constructs::fromDirectory($source);
        $mapped = array_map(fn($construct) => $construct->name(), $constructs);
        $parsed = array_map(fn($class) => $this->recorder->create($class), $mapped);

        return new ClassList($parsed);
    }
}
