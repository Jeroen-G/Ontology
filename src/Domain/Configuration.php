<?php

declare(strict_types=1);

namespace JeroenG\Ontology\Domain;

use JeroenG\Ontology\Domain\Runners\GeneratorRunnerInterface;
use JeroenG\Ontology\Domain\Runners\ValidationRunnerInterface;
use JeroenG\Ontology\Domain\Validators\GeneratorRegistry;
use JeroenG\Ontology\Domain\Validators\ValidatorRegistry;

final class Configuration
{
    public function __construct(
        private array $domainLayers,
        private array $infrastructureLayers,
        private array $applicationLayers,
        private array $validators,
        private array $generators,
        private ValidationRunnerInterface $validationRunner,
        private GeneratorRunnerInterface $generatorRunner,
    ) {
    }

    public function isDomainLayer(string $namespace): bool
    {
        return $this->inLayers($namespace, $this->domainLayers);
    }

    public function isApplicationLayer(string $namespace): bool
    {
        return $this->inLayers($namespace, $this->applicationLayers);
    }

    public function isInfrastructureLayer(string $namespace): bool
    {
        return $this->inLayers($namespace, $this->infrastructureLayers);
    }

    public function getValidationRunner(): ValidationRunnerInterface
    {
        $this->validationRunner->setValidators($this->getValidatorRegistry()->all());
        return $this->validationRunner;
    }

    public function getGeneratorRunner(): GeneratorRunnerInterface
    {
        $this->generatorRunner->setGenerators($this->getGeneratorRegistry()->all());
        return $this->generatorRunner;
    }

    public function getValidatorRegistry(): ValidatorRegistry
    {
        return new ValidatorRegistry($this->validators, $this);
    }

    public function getGeneratorRegistry(): GeneratorRegistry
    {
        return new GeneratorRegistry($this->generators, $this);
    }

    private function inLayers(string $namespace, array $layers): bool
    {
        $filtered = array_filter($layers, fn ($layer) => str_contains($namespace, $layer));
        return count($filtered) > 0;
    }
}
