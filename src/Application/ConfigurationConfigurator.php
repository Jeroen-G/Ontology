<?php

declare(strict_types=1);

namespace JeroenG\Ontology\Application;

use JeroenG\Ontology\Domain\Configuration;
use JeroenG\Ontology\Domain\Runners\MarkdownRunner;
use JeroenG\Ontology\Domain\Runners\ValidationRunner;
use JeroenG\Ontology\Infrastructure\FileCollector;
use JeroenG\Ontology\Infrastructure\Filesystem\FilesystemFactory;
use JeroenG\Ontology\Infrastructure\ReflectionRecorder;

final class ConfigurationConfigurator
{
    private array $domainLayers = [];

    private array $infrastructureLayers = [];

    private array $applicationLayers = [];

    private array $validators = [];

    private array $generators = [];

    public function setDomainLayers(array $domainLayers): self
    {
        $this->domainLayers = $domainLayers;
        return $this;
    }

    public function setInfrastructureLayers(array $infrastructureLayers): self
    {
        $this->infrastructureLayers = $infrastructureLayers;
        return $this;
    }

    public function setApplicationLayers(array $applicationLayers): self
    {
        $this->applicationLayers = $applicationLayers;
        return $this;
    }

    public function setValidators(array $validators): self
    {
        $this->validators = $validators;
        return $this;
    }

    public function setGenerators(array $generators): self
    {
        $this->generators = $generators;
        return $this;
    }

    public function getFilesystemAdapter(): FilesystemInterface
    {
        return FilesystemFactory::createWithLocalFlysystem(getcwd());
    }

    public function getFileCollector(): CollectorInterface
    {
        return new FileCollector(new ReflectionRecorder());
    }

    public function configure(): Configuration
    {
        return new Configuration(
            $this->domainLayers,
            $this->infrastructureLayers,
            $this->applicationLayers,
            $this->validators,
            $this->generators,
            new ValidationRunner(),
            new MarkdownRunner($this->getFilesystemAdapter()),
        );
    }
}
