<?php

declare(strict_types=1);

namespace JeroenG\Ontology\Domain\Validators;

use JeroenG\Ontology\Domain\Configuration;
use JeroenG\Ontology\Domain\Generators\GeneratorInterface;
use Roave\BetterReflection\Reflection\ReflectionClass;

final class GeneratorRegistry
{
    public function __construct(
        private array $generators,
        private Configuration $configuration,
    ) {
    }

    public function all(): array
    {
        return array_map(fn (string $g) => $this->init($g), $this->generators);
    }

    private function init(string $generatorClass): GeneratorInterface
    {
        $reflectionClass = ReflectionClass::createFromName($generatorClass);

        if ($reflectionClass->implementsInterface(ConfigurableInterface::class)) {
            return new $generatorClass($this->configuration);
        }

        return new $generatorClass();
    }
}
