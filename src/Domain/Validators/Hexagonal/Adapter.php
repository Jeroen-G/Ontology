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
use Roave\BetterReflection\Reflection\ReflectionClass;

final class Adapter implements ValidatorInterface, ConfigurableInterface
{
    public function __construct(
        private Configuration $configuration,
    ){
    }

    public function supports(string $type): bool
    {
        return $type === AdapterAttribute::class;
    }

    public function valid(Record $record): bool
    {
        $reflectionClass = $record->reflectionClass;
        $namespace = $reflectionClass->getNamespaceName();

        if(!$this->configuration->isInfrastructureLayer($namespace)) {
            return false;
        }

        $interfaces = $reflectionClass->getInterfaces();

        if (empty($interfaces)) {
            return false;
        }

        foreach ($interfaces as $interface) {
            if ($this->interfaceIsAPort($interface)) {
                return true;
            }
        }

        return false;
    }

    public function getErrorMessage(): string
    {
        return 'Adapters should be part of the infrastructure layer and have a corresponding Port.';
    }

    private function interfaceIsAPort(ReflectionClass $interface): bool
    {
        foreach ($interface->getAttributes() as $attribute) {
            $name = $attribute->getName();
            if ($name === PortAttribute::class || $name === RepositoryAttribute::class ) {
                return true;
            }
        }

        return false;
    }
}
