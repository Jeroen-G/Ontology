<?php

declare(strict_types=1);

namespace JeroenG\Ontology\Domain\Generators;

use JeroenG\Ontology\Domain\Attributes\Www;
use JeroenG\Ontology\Domain\ClassList;
use JeroenG\Ontology\Domain\Filesystem\Directory;
use JeroenG\Ontology\Domain\Filesystem\File;
use JeroenG\Ontology\Domain\Record;

final class Bibliography implements GeneratorInterface
{
    use ExtractorTrait;

    public function supports(string $type): bool
    {
        return $type === Www::class;
    }

    public function generate(ClassList $list): Directory
    {
        $file = new File('bibliography.md', "# Bibliography\n\n");

        /** @var Record $record */
        foreach ($list->iterator() as $record) {
            if (!$record->hasAttribute(Www::class)) {
                continue;
            }

            $title = $record->reflectionClass->getName();
            $text = "### $title\n";
            $links = $this->extractLinks($record);
            $text .= $links;

            $file->append($text . "\n\n");
        }

        return new Directory('', [$file]);
    }

    private function extractLinks(Record $record): string
    {
        $attributes = $this->extractAttribute(Www::class, $record);

        $links = '';
        foreach ($attributes as $attribute) {
            /** @var Link $instance */
            $instance = $attribute->newInstance();

            if ($instance->hasName()) {
                $link = "[{$instance->getName()}]({$instance->getLink()})";
            } else {
                $link = $instance->getLink();
            }

            $links .= "- " . $link . "\n";
        }

        return $links;
    }
}
