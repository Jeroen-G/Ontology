<?php

declare(strict_types=1);

namespace JeroenG\Ontology\Domain\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Tag
{
    public function __construct(
        private string $tag
    ) {
    }

    public function getTag(): string
    {
        return $this->tag;
    }
}
