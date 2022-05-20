<?php

declare(strict_types=1);

namespace JeroenG\Ontology\Domain\Filesystem;

final class File
{
    public function __construct(
        private string $name,
        private string $content = '',
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function append(string $data): void
    {
        $this->content .= $data;
    }
}
