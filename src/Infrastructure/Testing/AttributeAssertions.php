<?php

declare(strict_types=1);

namespace JeroenG\Ontology\Infrastructure;

trait AttributeAssertions
{
    public static function assertNoMethodNamesContaining(array $methods, string $needle): void
    {
        /** @var \ReflectionMethod $method */
        foreach ($methods as $method) {
            self::assertFalse(str_starts_with($method->name, $needle), "Found $needle in $method->name");
        }
    }
}
