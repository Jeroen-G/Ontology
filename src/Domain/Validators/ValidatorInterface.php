<?php

declare(strict_types=1);

namespace JeroenG\Ontology\Domain\Validators;

use JeroenG\Ontology\Domain\Record;

interface ValidatorInterface
{
    public function supports(string $type): bool;

    public function valid(Record $record): bool;

    /** Answer this question: "It went wrong because..." */
    public function getErrorMessage(): string;
}
