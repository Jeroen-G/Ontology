<?php

declare(strict_types=1);

namespace JeroenG\Ontology\Infrastructure;

use JeroenG\Ontology\Application\RecorderInterface;
use JeroenG\Ontology\Domain\Document;
use JeroenG\Ontology\Domain\Element;
use JeroenG\Ontology\Domain\Record;
use Roave\BetterReflection\Reflection\ReflectionClass;

final class ReflectionRecorder implements RecorderInterface
{
    public function create(string $class): Record
    {
        $reflectionClass = ReflectionClass::createFromName($class);

        return new Record(
            name: $reflectionClass->getName(),
            reflectionClass: $reflectionClass,
        );
    }
}
