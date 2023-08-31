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

    /**
     * @return string[]
     */
    public function getExcludedPaths(): array
    {
        /** @var string[] */
        $excludedPaths = $this->parameters['excludedPaths'] ?? $this->parameters['excludedPaths'];

        Assert::allFileExists($excludedPaths);

        return $excludedPaths;
    }
}
