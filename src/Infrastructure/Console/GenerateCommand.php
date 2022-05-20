<?php

declare(strict_types=1);

namespace JeroenG\Ontology\Infrastructure\Console;

use JeroenG\Ontology\Application\CollectorInterface;
use JeroenG\Ontology\Application\FilesystemInterface;
use JeroenG\Ontology\Domain\Configuration;
use JeroenG\Ontology\Domain\Runners\GeneratorRunnerInterface;
use JeroenG\OntologyPublish\NextPathSource;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCommand extends Command
{
    protected static $defaultName = 'generate';

    private bool $passed = true;

    private CollectorInterface $collector;

    private Configuration $configuration;

    private Renderer $render;

    private FilesystemInterface $filesystem;

    public function __construct(CollectorInterface $collector, Configuration $configuration, FilesystemInterface $filesystem)
    {
        parent::__construct();

        $this->collector = $collector;
        $this->configuration = $configuration;
        $this->filesystem = $filesystem;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Generate documentation')
            ->addArgument('directories', InputArgument::IS_ARRAY, 'Directories you want to validate.')
            ->addOption('public', null, InputOption::VALUE_NONE, 'Create a publishable version of the docs')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->render = new Renderer($input->getOption('no-ansi') ?? false);
        $generators = $this->configuration->getGeneratorRegistry()->all();
        $runner = $this->configuration->getGeneratorRunner();

        foreach ($input->getArgument('directories') as $directory) {
            $this->render->title("Generating $directory");
            $this->runRecords($directory, $runner);
        }

        if ($this->passed) {
            $this->render->success("OK (" . count($generators) . " generators)");
        }

        // Brace yourselves, incoming embarrassing quickfix
        if ($input->getOption('public')) {
            $packageFrom = realpath(NextPathSource::getPath());
            $docs = getcwd().'/docs';
            $publicFolder = $docs.'/public';
            $temp = getcwd().'/docs-temp';
            $publicDocs = $publicFolder.'/_docs';

            shell_exec("rm -rf $publicFolder");
            shell_exec("cp -r $docs/. $temp");
            shell_exec("cp -r $packageFrom $publicFolder");
            shell_exec("cp -r $temp/. $publicDocs");
            shell_exec("rm -rf $temp");
        }

        return $this->passed ? Command::SUCCESS : Command::FAILURE;
    }

    private function runRecords(string $directory, GeneratorRunnerInterface $runner): void
    {
        try {
            $records = $this->collector->collect($directory);
            $runner->run($records);
        } catch (\Exception $exception) {
            $this->passed = false;
            $this->render->error($exception->getMessage(), [$exception->getTraceAsString()]);
        }
    }
}
