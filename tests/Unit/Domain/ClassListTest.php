<?php

declare(strict_types=1);

namespace JeroenG\Tests\Unit\Domain;

use JeroenG\Ontology\Domain\Attributes\Glossary;
use JeroenG\Ontology\Domain\Attributes\ValueObject;
use JeroenG\Ontology\Domain\ClassList;
use JeroenG\Ontology\Domain\Record;
use JeroenG\Ontology\Tests\Support\AttributeHandling;
use PHPUnit\Framework\TestCase;

final class ClassListTest extends TestCase
{
    use AttributeHandling;

    public function test_it_can_extract_unique_attributes(): void
    {
        $a = new #[Glossary('Foo bar')] class {};
        $b = new #[Glossary('Bar foo')] class {};
        $c = new #[ValueObject] class {};

        $list = new ClassList([
            $this->makeRecord($a),
            $this->makeRecord($b),
            $this->makeRecord($c),
        ]);

        $attrList = $list->getUniqueAttributes();

        self::assertCount(2, $attrList);
    }

    private function makeRecord(object $class): Record
    {
        $reflectionClass = self::getReflectionClass($class);
        return new Record($reflectionClass->getName(), $reflectionClass);
    }
}
