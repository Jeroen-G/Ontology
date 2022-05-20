<?php

declare(strict_types=1);

namespace JeroenG\Ontology\Domain\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class GuidedTour
{
    public function __construct(
        private string $tourName,
        private string $description,
        private int $order = 0,
    ) {
    }

    public function getOrder(): int
    {
        return $this->order;
    }

    public function getTourName(): string
    {
        return $this->tourName;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
