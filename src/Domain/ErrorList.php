<?php

declare(strict_types=1);

namespace JeroenG\Ontology\Domain;

final class ErrorList
{
    private array $errors = [];

    public function addTo($key, $value): void
    {
        $this->errors[$key][] = $value;
    }

    public function empty(): bool
    {
        return empty($this->errors);
    }

    public function hasErrors(): bool
    {
        return !$this->empty();
    }

    public function all(): array
    {
        return $this->errors;
    }
}
