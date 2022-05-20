<?php

declare(strict_types=1);

namespace JeroenG\Ontology\Domain\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Glossary
{
    public function __construct(
        private string $summary
    ) {
    }

    public function getSummary(): string
    {
        return $this->summary;
    }
}
