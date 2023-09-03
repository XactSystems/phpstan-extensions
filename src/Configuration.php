<?php

declare(strict_types=1);

namespace Xact\PHPStan;

use Webmozart\Assert\Assert;

final class Configuration
{
    /** @var array<string, mixed> $parameters */
    private array $parameters;

    /**
     * @param array<string, mixed> $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    public function isUnusedClassesEnabled(): bool
    {
        return (bool)($this->parameters['classes'] ?? true);
    }

    public function isUnusedTraitsEnabled(): bool
    {
        return (bool)($this->parameters['traits'] ?? true);
    }

    /**
     * @return string[]
     */
    public function getExcludePaths(): array
    {
        /** @var string[] */
        $excludePaths = $this->parameters['excludePaths'] ?? $this->parameters['excludePaths'];

        Assert::allFileExists($excludePaths);

        return $excludePaths;
    }

    public function isExcludeFrameworks(): bool
    {
        return (bool)($this->parameters['excludeFrameworks'] ?? true);
    }

    /**
     * @return string[]
     */
    public function getBaseClassExcludes(): array
    {
        /** @var string[] */
        $frameworkClasses = $this->parameters['baseClassExcludes'] ?? $this->parameters['baseClassExcludes'];

        Assert::isArray($frameworkClasses);

        return $frameworkClasses;
    }
}
