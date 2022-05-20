<?php

declare(strict_types=1);

namespace JeroenG\Ontology\Domain\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class ExternalActor
{
    public function __construct(
        private string $description
    ) {
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
