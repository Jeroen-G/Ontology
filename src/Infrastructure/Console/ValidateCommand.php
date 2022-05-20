<?php

declare(strict_types=1);

namespace JeroenG\Ontology\Infrastructure\Console;

use JeroenG\Ontology\Application\CollectorInterface;
use JeroenG\Ontology\Domain\Configuration;
use JeroenG\Ontology\Domain\Runners\ValidationRunnerInterface;
use JeroenG\Ontology\Domain\Validators\ValidationException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ValidateCommand extends Command
{
    protected static $defaultName = 'validate';

    private bool $passed = true;

    private CollectorInterface $collector;

    private Configuration $configuration;

    private Renderer $render;

    public function __construct(CollectorInterface $collector, Configuration $configuration)
    {
        parent::__construct();

        $this->collector = $collector;
        $this->configuration = $configuration;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Validate based on the annotations')
            ->addArgument('directories', InputArgument::IS_ARRAY, 'Directories you want to validate.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->render = new Renderer($input->getOption('no-ansi') ?? false);
        $runner = $this->configuration->getValidationRunner();

        foreach ($input->getArgument('directories') as $directory) {
            $this->render->title("Validating $directory");
            $this->runRecords($directory, $runner);
        }

        if ($this->passed) {
            $this->render->success("OK (" . $this->configuration->getValidatorRegistry()->count() . " validators)");
        }

        return $this->passed ? Command::SUCCESS : Command::FAILURE;
    }

    private function runRecords(string $directory, ValidationRunnerInterface $runner): void
    {
        try {
            $records = $this->collector->collect($directory);
            $runner->run($records);
        } catch (ValidationException $exception) {
            $this->passed = false;
            foreach ($exception->getErrors() as $subject => $messages) {
                $this->render->error($subject, $messages);
            }
        }
    }

//    private function getValidators(?string $input): array
//    {
//        $validatorRegistry = $this->configuration->getValidatorRegistry();
//
//        if ($input === '' || $input === null) {
//            return $validatorRegistry->all();
//        }
//
//        return $validatorRegistry->match([$input]);
//    }
}
