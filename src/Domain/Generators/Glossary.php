<?php

declare(strict_types=1);

namespace JeroenG\Ontology\Domain\Generators;

use JeroenG\Ontology\Domain\Attributes\Glossary as GlossaryAttribute;
use JeroenG\Ontology\Domain\ClassList;
use JeroenG\Ontology\Domain\Filesystem\Directory;
use JeroenG\Ontology\Domain\Filesystem\File;
use JeroenG\Ontology\Domain\Record;
use Webmozart\Assert\Assert;

final class Glossary implements GeneratorInterface
{
    public function supports(string $type): bool
    {
        return $type === GlossaryAttribute::class;
    }

    public function generate(ClassList $list): Directory
    {
        $file = new File('glossary.md', "# Glossary\n\n");

        /** @var Record $record */
        foreach ($list->iterator() as $record) {
            if (!$record->hasAttribute(GlossaryAttribute::class)) {
                continue;
            }

            $glossary = $this->extractGlossary($record);
            $summary = $glossary->getSummary();
            $title = $record->reflectionClass->getName();
            $text = "### $title\n$summary";

            $file->append($text);
        }

        return new Directory('', [$file]);
    }

    private function extractGlossary(Record $record): GlossaryAttribute
    {
        $class = new \ReflectionClass($record->name);
        $reflectionAttributes = $class->getAttributes(GlossaryAttribute::class);

        Assert::count($reflectionAttributes, 1);

        return $reflectionAttributes[0]->newInstance();
    }
}
