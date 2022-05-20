<?php

declare(strict_types=1);

namespace JeroenG\Ontology\Domain\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Www
{
    public function __construct(
        private string $name,
        private string $link,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLink(): string
    {
        return $this->link;
    }
}
