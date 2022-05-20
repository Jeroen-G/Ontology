<?php

declare(strict_types=1);

namespace JeroenG\Tests\Unit\Domain\Runners;

use JeroenG\Ontology\Domain\ClassList;
use JeroenG\Ontology\Domain\Record;
use JeroenG\Ontology\Domain\Runners\ValidationRunner;
use JeroenG\Ontology\Domain\Validators\ValidationException;
use JeroenG\Tests\Fakes as Fakes;
use JeroenG\Tests\Support\EmptyValidator;
use JeroenG\Tests\Support\NopeValidator;
use JeroenG\Tests\Support\YesManValidator;
use PHPUnit\Framework\TestCase;
use Roave\BetterReflection\Reflection\ReflectionClass;

final class ValidationRunnerTest extends TestCase
{
    public function test_it_throws_exception_when_validators_are_not_set(): void
    {
        $class = ReflectionClass::createFromName(Fakes\Valid\ValueObject::class);
        $record = new Record($class->getName(), $class);
        $runner = new ValidationRunner();

        $this->expectErrorMessage('$validators must not be accessed before initialization');

        $runner->run(new ClassList([$record]));
    }

    public function test_it_runs(): void
    {
        $class = ReflectionClass::createFromName(Fakes\Valid\ValueObject::class);
        $record = new Record($class->getName(), $class);
        $runner = new ValidationRunner();
        $runner->setValidators([new YesManValidator()]);

        $this->expectNotToPerformAssertions();

        $runner->run(new ClassList([$record]));
    }

    public function test_it_throws_exception_for_invalid_records(): void
    {
        $class = ReflectionClass::createFromName(Fakes\Invalid\ValueObject::class);
        $record = new Record($class->getName(), $class);
        $runner = new ValidationRunner();
        $runner->setValidators([new NopeValidator()]);

        $this->expectException(ValidationException::class);

        $runner->run(new ClassList([$record]));
    }

    public function test_it_finds_the_right_validator(): void
    {
        $class = ReflectionClass::createFromName(Fakes\Invalid\ValueObject::class);
        $record = new Record($class->getName(), $class);
        $runner = new ValidationRunner();
        $runner->setValidators([new YesManValidator(), new EmptyValidator()]);

        $this->expectNotToPerformAssertions();

        $runner->run(new ClassList([$record]));
    }
}
