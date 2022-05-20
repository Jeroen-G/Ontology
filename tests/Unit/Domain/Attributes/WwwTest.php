<?php

declare(strict_types=1);

namespace JeroenG\Tests\Unit\Domain\Attributes;

use JeroenG\Ontology\Domain\Attributes\Www;
use JeroenG\Ontology\Tests\Support\AttributeHandling;
use PHPUnit\Framework\TestCase;

final class WwwTest extends TestCase
{
    use AttributeHandling;

    public function test_a_class_can_have_external_links(): void
    {
        $class = new #[Www('Foo bar', 'https://youtu.be/dQw4w9WgXcQ')] class {};
        $attr = self::getAttributeFromClass($class, Www::class);

        self::assertAttributeExists($class, Www::class);
        self::assertAttributeHasArgument($attr, 'Foo bar');
        self::assertAttributeHasArgument($attr, 'https://youtu.be/dQw4w9WgXcQ');
    }
}
