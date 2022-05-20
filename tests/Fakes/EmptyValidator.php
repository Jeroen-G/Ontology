<?php

declare(strict_types=1);

namespace JeroenG\Tests\Support;

use JeroenG\Ontology\Domain\Record;
use JeroenG\Ontology\Domain\Validators\ValidatorInterface;

final class EmptyValidator implements ValidatorInterface
{
    public function supports(string $type): bool
    {
        return false;
    }

    public function valid(Record $record): bool
    {
        return true === false;
    }

    public function getErrorMessage(): string
    {
        return '';
    }
}
