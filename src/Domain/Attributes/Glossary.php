<?php

declare(strict_types=1);

namespace JeroenG\Ontology\Domain\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
/** Give a summary of what this class is about for the glossary. */
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
