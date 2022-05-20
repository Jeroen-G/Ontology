<?php

declare(strict_types=1);

namespace JeroenG\Ontology\Domain\Runners;

use JeroenG\Ontology\Domain\ClassList;

interface ValidationRunnerInterface
{
    public function run(ClassList $list): void;

    public function setValidators(array $validators): void;
}
