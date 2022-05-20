<?php

declare(strict_types=1);

namespace JeroenG\Ontology\Domain\Filesystem;

use Webmozart\Assert\Assert;

final class Directory
{
    public function __construct(
        private string $name,
        private array $files,
    ) {
        Assert::allIsInstanceOf($this->files, File::class);
    }

    public function getName(): string
    {
        return $this->name;
    }

    /** @return File[] */
    public function getFiles(): array
    {
        return $this->files;
    }
}
