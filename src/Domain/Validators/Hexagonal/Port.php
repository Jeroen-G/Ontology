<?php

declare(strict_types=1);

namespace JeroenG\Ontology\Domain\Validators\Hexagonal;

use JeroenG\Ontology\Domain\Attributes\Port as PortAttribute;
use JeroenG\Ontology\Domain\Configuration;
use JeroenG\Ontology\Domain\Record;
use JeroenG\Ontology\Domain\Validators\ConfigurableInterface;
use JeroenG\Ontology\Domain\Validators\ValidatorInterface;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflection\ReflectionClass;

final class Port implements ValidatorInterface, ConfigurableInterface
{
    public function __construct(
        private Configuration $configuration,
    ){
    }

    public function supports(string $type): bool
    {
        return $type === PortAttribute::class;
    }

    public function valid(Record $record): bool
    {
        $reflectionClass = $record->reflectionClass;
        $namespace = $reflectionClass->getNamespaceName();

        if (!$reflectionClass->isInterface()) {
            return false;
        }

        if(!$this->configuration->isDomainLayer($namespace)) {
            return false;
        }

        return true;
    }

    public function getErrorMessage(): string
    {
        return 'Ports are meant to be interfaces in the Domain layer.';
    }
}
