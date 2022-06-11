<?php

declare(strict_types=1);

namespace JeroenG\Ontology\Domain\Validators;

use JeroenG\Ontology\Domain\Configuration;
use Roave\BetterReflection\Reflection\ReflectionClass;

final class ValidatorRegistry
{
    public function __construct(
        private array $validators,
        private Configuration $configuration,
    ) {
    }

    public function all(): array
    {
        return $this->make($this->validators);
    }

    public function match(array $keys): array
    {
        $matches = array_filter(
            $this->validators,
            fn ($v) => in_array($v, $keys, true),
            ARRAY_FILTER_USE_KEY
        );

        return $this->make($this->flatten($matches));
    }

    public function count(): int
    {
        return count($this->validators);
    }

    private function make(array $validators): array
    {
        return array_map(
            fn ($validator) =>
            is_array($validator)
                ? $this->match($validator)
                : $this->init($validator),
            array_values($validators)
        );
    }

    private function init(string $validatorClass): ValidatorInterface
    {
        $reflectionClass = ReflectionClass::createFromName($validatorClass);

        if ($reflectionClass->implementsInterface(ConfigurableInterface::class)) {
            return new $validatorClass($this->configuration);
        }

        return new $validatorClass();
    }

    private function flatten(array $array): array
    {
        $result = [];

        foreach ($array as $item) {
            if (!is_array($item)) {
                $result[] = $item;
            } else {
                $values = self::flatten($item);

                foreach ($values as $value) {
                    $result[] = $value;
                }
            }
        }

        return $result;
    }
}
