<?php

declare(strict_types=1);

namespace JeroenG\Ontology\Tests\Support;

use Roave\BetterReflection\Reflection\ReflectionAttribute;
use Roave\BetterReflection\Reflection\ReflectionClass;

use function PHPUnit\Framework\assertContains;

trait AttributeHandling
{
    public static function getReflectionClass(string|object $class): ReflectionClass
    {
        if (is_string($class)) {
            return ReflectionClass::createFromName($class);
        }

        if ($class instanceof ReflectionClass) {
            return $class;
        }

        return ReflectionClass::createFromInstance($class);
    }

    /** @return ReflectionAttribute[] */
    public static function getAttributesFromClass(string|object $class): array
    {
        return (self::getReflectionClass($class))->getAttributes();
    }

    public static function getAttributeFromClass(string|object $class, string $attributeName): ReflectionAttribute
    {
        $attributes = (self::getReflectionClass($class))->getAttributesByName($attributeName);

        if (empty($attributes)) {
            throw new \ReflectionException("Could not find attribute $attributeName!");
        }

        if (count($attributes) > 1) {
            throw new \ReflectionException(
                "Multiple $attributeName attributes found, please use getAttributesFromClass() instead."
            );
        }

        return $attributes[0];
    }

    public static function assertAttributeExists(string|object $class, string $attribute): void
    {
        $attributeNames = array_map(
            fn(ReflectionAttribute $refAttr) => $refAttr->getName(),
            self::getAttributesFromClass($class)
        );

        assertContains($attribute, $attributeNames);
    }

    public static function assertAttributeHasArgument(ReflectionAttribute $attribute, mixed $argument): void
    {
        $arguments = $attribute->getArguments();

        assertContains($argument, $arguments);
    }
}
