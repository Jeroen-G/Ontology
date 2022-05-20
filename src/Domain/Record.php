<?php

declare(strict_types=1);

namespace JeroenG\Ontology\Domain;

use Roave\BetterReflection\Reflection\ReflectionClass;

final class Record
{
    public function __construct(
        public string $name,
        public ReflectionClass $reflectionClass,
    ){
    }

    public function getAttributes(): array
    {
        return $this->reflectionClass->getAttributes();
    }

    public function hasAttribute(string $name): bool
    {
        return count($this->reflectionClass->getAttributesByName($name)) > 0;
    }
}
