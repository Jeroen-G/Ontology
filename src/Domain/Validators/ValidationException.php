<?php

declare(strict_types=1);

namespace JeroenG\Ontology\Domain\Validators;

use JeroenG\Ontology\Domain\ErrorList;

final class ValidationException extends \DomainException
{
    private array $errors;

    public static function forErrors(ErrorList $errors): ValidationException
    {
        $e = new self();
        $e->errors = $errors->all();
        return $e;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
