<?php

declare(strict_types=1);

namespace JeroenG\Ontology\Domain\Generators;

use JeroenG\Ontology\Domain\Record;
use Webmozart\Assert\Assert;

trait ExtractorTrait
{
    private function extractAttribute(string $attribute, Record $record): array
    {
        $class = new \ReflectionClass($record->name);
        $reflectionAttributes = $class->getAttributes($attribute);

        Assert::count($reflectionAttributes, 1);

        return $reflectionAttributes;
    }
}
