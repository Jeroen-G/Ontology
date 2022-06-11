<?php

declare(strict_types=1);

namespace JeroenG\Ontology\Domain\Attributes;

use Attribute;

#[Attribute(
    Attribute::IS_REPEATABLE
    | Attribute::TARGET_METHOD
    | Attribute::TARGET_PROPERTY
)]
class PointOfInterest
{
    public function __construct(
        private string $description,
        private int $order = 0,
    ) {
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getOrder(): int
    {
        return $this->order;
    }
}
