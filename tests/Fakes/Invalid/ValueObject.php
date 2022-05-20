<?php

declare(strict_types=1);

namespace JeroenG\Tests\Fakes\Invalid;

use JeroenG\Ontology\Domain\Attributes as DDD;

#[DDD\ValueObject]
final class ValueObject
{
    public function setValue($value): void
    {
    }
}
