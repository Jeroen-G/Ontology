<?php

declare(strict_types=1);

namespace JeroenG\Ontology\Domain\Generators;

use JeroenG\Ontology\Domain\Attributes\GuidedTour as GuidedTourAttribute;
use JeroenG\Ontology\Domain\Attributes\PointOfInterest;
use JeroenG\Ontology\Domain\ClassList;
use JeroenG\Ontology\Domain\Filesystem\Directory;
use JeroenG\Ontology\Domain\Filesystem\File;
use JeroenG\Ontology\Domain\Record;
use Webmozart\Assert\Assert;

final class GuidedTour implements GeneratorInterface
{
    public function supports(string $type): bool
    {
        return $type === GuidedTourAttribute::class;
    }

    public function generate(ClassList $list): Directory
    {
        $files = [];

        $tours = $this->extractTours($list);

        foreach ($tours as $tourName => $tourStops) {
            $file = new File("$tourName.md", "# $tourName\n\n");

            foreach ($tourStops as $tourStop) {
                ['class' => $class, 'description' => $description] = $tourStop;
                $text = "### $class\n$description";

                $text .= "\n\n#### Points of Interest\n\n";

                foreach ($tourStop['points'] as $pointOfInterest) {
                    ['location' => $location, 'name' => $name] = $pointOfInterest;
                    $text .= "```php\n$location\n```\n$name\n";
                }

                $file->append($text);
            }

            $files[] = $file;
        }

        return new Directory('tours', $files);
    }

    private function extractTours(ClassList $list): array
    {
        $tours = [];

        /** @var Record $record */
        foreach ($list->iterator() as $record) {
            if (!$record->hasAttribute(GuidedTourAttribute::class)) {
                continue;
            }

            $tour = $this->extractGuidedTour($record);
            $pointsOfInterest = $this->extractPointsOfInterest($record);

            $tours[$tour->getTourName()][] = [
                'class' => $record->name,
                'order' => $tour->getOrder(),
                'description' => $tour->getDescription(),
                'points' => $pointsOfInterest,
            ];
        }

        return $tours;
    }

    private function extractGuidedTour(Record $record): GuidedTourAttribute
    {
        $class = new \ReflectionClass($record->name);
        $reflectionAttributes = $class->getAttributes(GuidedTourAttribute::class);

        Assert::count($reflectionAttributes, 1);

        return $reflectionAttributes[0]->newInstance();
    }

    private function extractPointsOfInterest(Record $record): array
    {
        $class = new \ReflectionClass($record->name);
        $pointsOfInterest = [];

        foreach ($class->getMethods() as $method) {
            foreach ($method->getAttributes(PointOfInterest::class) as $methodAttribute) {
                $pointsOfInterest[] = [
                    'location' => $record->name.'::'.$method->getName().'()',
                    'name' => $methodAttribute->newInstance()->getDescription(),
                ];
            }
        }

        foreach ($class->getProperties() as $property) {
            foreach ($property->getAttributes(PointOfInterest::class) as $propertyAttribute) {
                $pointsOfInterest[] = [
                    'location' => '$'.$property->getName().' = '.($property->getDefaultValue() ?? 'null'),
                    'name' => $propertyAttribute->newInstance()->getDescription(),
                    'order' => $propertyAttribute->newInstance()->getOrder(),
                ];
            }
        }

        return $pointsOfInterest;
    }
}
