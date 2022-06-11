<?php

declare(strict_types=1);

namespace JeroenG\Ontology\Domain\Runners;

use JeroenG\Ontology\Domain\ClassList;
use JeroenG\Ontology\Domain\ErrorList;
use JeroenG\Ontology\Domain\Record;
use JeroenG\Ontology\Domain\Validators\ValidationException;
use JeroenG\Ontology\Domain\Validators\ValidatorInterface;
use Webmozart\Assert\Assert;

final class ValidationRunner implements ValidationRunnerInterface
{
    /** @var ValidatorInterface[] */
    private array $validators;

    private ErrorList $errors;

    public function setValidators(array $validators): void
    {
        Assert::allIsInstanceOf($validators, ValidatorInterface::class);
        $this->validators = $validators;
    }

    public function run(ClassList $list): void
    {
        $this->errors = new ErrorList();
        foreach ($list->iterator() as $record) {
            $this->forRecord($record);
        }

        if ($this->errors->hasErrors()) {
            throw ValidationException::forErrors($this->errors);
        }
    }

    private function forRecord(Record $record): void
    {
        /** @var \ReflectionAttribute $attribute */
        foreach ($record->getAttributes() as $attribute) {
            $validators = $this->getValidatorsForType($attribute->getName());
            foreach ($validators as $validator) {
                if ($validator->valid($record) === false) {
                    $this->errors->addTo($record->name, $validator->getErrorMessage());
                }
            }
        }
    }

    /** @return ValidatorInterface[] */
    private function getValidatorsForType(string $type): array
    {
        return array_filter($this->validators, fn (ValidatorInterface $v) => $v->supports($type));
    }
}
