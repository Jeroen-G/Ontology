<?php

declare(strict_types=1);

namespace JeroenG\Ontology\Domain\Validators\ValueObject;

use JeroenG\Ontology\Domain\Attributes\ValueObject;
use JeroenG\Ontology\Domain\Record;
use JeroenG\Ontology\Domain\Validators\ValidatorInterface;

final class Immutable implements ValidatorInterface
{
    public function supports(string $type): bool
    {
        return $type === ValueObject::class;
    }

    public function valid(Record $record): bool
    {
        $reflectionClass = $record->reflectionClass;

        if ($reflectionClass->hasMethod('__set')) {
            return false;
        }

        foreach ($reflectionClass->getMethods() as $method) {
            if ($method->isPublic() && str_starts_with($method->getName(), 'set')) {
                return false;
            }
        }

        foreach ($reflectionClass->getProperties() as $property) {
            if ($property->isPublic() && !$property->isReadOnly()) {
                return false;
            }
        }

        return true;
    }

    public function getErrorMessage(): string
    {
        return 'The state of an immutable class may not be changed.';
    }
}
