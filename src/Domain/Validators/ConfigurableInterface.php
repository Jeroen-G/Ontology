<?php

declare(strict_types=1);

namespace JeroenG\Ontology\Domain\Validators;

use JeroenG\Ontology\Domain\Configuration;

interface ConfigurableInterface
{
    public function __construct(Configuration $configuration);
}
