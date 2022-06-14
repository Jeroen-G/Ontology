<?php

declare(strict_types=1);

namespace JeroenG\Ontology\Domain\Generators;

use JeroenG\Ontology\Domain\Attributes\Glossary as GlossaryAttribute;
use JeroenG\Ontology\Domain\Attributes\Link;
use JeroenG\Ontology\Domain\ClassList;
use JeroenG\Ontology\Domain\Filesystem\Directory;
use JeroenG\Ontology\Domain\Filesystem\File;
use JeroenG\Ontology\Domain\Record;

final class Glossary implements GeneratorInterface
{
    use ExtractorTrait;

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

            if ($record->hasAttribute(Link::class)) {
                $link = $this->extractLink($record);
                $text .= "\n\nMore information: $link";
            }

            $file->append($text."\n\n");
        }

        return new Directory('', [$file]);
    }

    private function extractGlossary(Record $record): GlossaryAttribute
    {
        $attributes = $this->extractAttribute(GlossaryAttribute::class, $record);
        return $attributes[0]->newInstance();
    }

    private function extractLink(Record $record): string
    {
        $attributes = $this->extractAttribute(Link::class, $record);

        $links = '';
        foreach ($attributes as $attribute) {
            /** @var Link $instance */
            $instance = $attribute->newInstance();
            $links .= "\n- ".$instance->getLink();
        }

        return $links;
    }
}
