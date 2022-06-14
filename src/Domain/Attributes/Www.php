<?php

declare(strict_types=1);

namespace JeroenG\Ontology\Domain\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
/** Reference external knowledge sources. */
class Www
{
    public function __construct(
        private ?string $name = null,
        private ?string $link = null,
    ) {
    }

    public function getName(): string
    {
        return $this->name ?? '';
    }

    public function getLink(): string
    {
        return $this->link ?? '';
    }

    public function hasName(): bool
    {
        return !is_null($this->name);
    }
}
