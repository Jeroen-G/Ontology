<?php

declare(strict_types=1);

namespace JeroenG\Ontology\Domain\Validators\Hexagonal;

use JeroenG\Ontology\Domain\Attributes\Adapter as AdapterAttribute;
use JeroenG\Ontology\Domain\Attributes\Port as PortAttribute;
use JeroenG\Ontology\Domain\Attributes\Repository as RepositoryAttribute;
use JeroenG\Ontology\Domain\Configuration;
use JeroenG\Ontology\Domain\Record;
use JeroenG\Ontology\Domain\Validators\ConfigurableInterface;
use JeroenG\Ontology\Domain\Validators\ValidatorInterface;

final class Repository implements ValidatorInterface, ConfigurableInterface
{
    private string $secondMessage;

    public function __construct(
        private Configuration $configuration,
    ){
    }

    public function supports(string $type): bool
    {
        return $type === RepositoryAttribute::class;
    }

    public function valid(Record $record): bool
    {
        $reflectionClass = $record->reflectionClass;

        if($reflectionClass->isInterface()) {
            $validator = (new Port($this->configuration));
        } else {
            $validator = (new Adapter($this->configuration));
        }

        if ($validator->valid($record)) {
            return true;
        }

        $this->secondMessage = $validator->getErrorMessage();

        return false;
    }

    public function getErrorMessage(): string
    {
        return 'Repositories must consist of a port and adapter. ' . $this->secondMessage;
    }
}
