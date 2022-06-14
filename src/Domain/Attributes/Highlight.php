<?php

declare(strict_types=1);

namespace JeroenG\Ontology\Domain\Attributes;

use Attribute;

#[Attribute(
    Attribute::IS_REPEATABLE
    | Attribute::TARGET_METHOD
    | Attribute::TARGET_PROPERTY
)]
class Highlight
{
    public function __construct(
        private string $description,
    ) {
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
