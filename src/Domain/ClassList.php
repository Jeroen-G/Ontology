<?php

declare(strict_types=1);

namespace JeroenG\Ontology\Domain;

use Roave\BetterReflection\Reflection\ReflectionAttribute;
use Webmozart\Assert\Assert;

final class ClassList
{
    /** @var Record[] */
    private array $classes;

    public function __construct(array $classes)
    {
        Assert::allIsInstanceOf($classes, Record::class);
        $this->classes = $classes;
    }

    public function iterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->classes);
    }

    public function getUniqueAttributes(): \ArrayIterator
    {
        $attributes = [];

        foreach ($this->classes as $record) {
            /** @var ReflectionAttribute $attribute */
            foreach ($record->getAttributes() as $attribute) {
                $attributes[] = $attribute->getName();
            }
        }

        $attributes = array_unique($attributes);

        return new \ArrayIterator($attributes);
    }
}
