<?php

declare(strict_types=1);

namespace JeroenG\Ontology\Domain\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
/** A value object is a type which wraps data and is distinguishable only by its properties. Unlike an Entity, it doesn't have a unique identifier. */
class ValueObject
{
}
