<?php

declare(strict_types=1);

namespace JeroenG\Tests\Unit\Domain\Attributes;

use JeroenG\Ontology\Domain\Attributes\Glossary;
use JeroenG\Ontology\Tests\Support\AttributeHandling;
use PHPUnit\Framework\TestCase;

final class GlossaryTest extends TestCase
{
    use AttributeHandling;

    public function test_a_class_can_have_summary_for_glossary(): void
    {
        $class = new #[Glossary('Foo bar')] class {};
        $attr = self::getAttributeFromClass($class, Glossary::class);

        self::assertAttributeExists($class, Glossary::class);
        self::assertAttributeHasArgument($attr, 'Foo bar');
    }
}
