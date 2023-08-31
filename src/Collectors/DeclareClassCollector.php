<?php

declare(strict_types=1);

namespace Xact\PHPStan\Collectors;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Collectors\Collector;
use PHPStan\File\FileHelper;
use Xact\PHPStan\Configuration;
use Xact\PHPStan\Exception\InvalidNodeTypeException;

class DeclareClassCollector implements Collector
{
    private Configuration $configuration;
    private FileHelper $fileHelper;

    public function __construct(Configuration $configuration, FileHelper $fileHelper)
    {
        $this->configuration = $configuration;
        $this->fileHelper = $fileHelper;
    }

    public function getNodeType(): string
    {
        return Class_::class;
    }

    /**
     * @inheritDoc
     */
    // phpcs:ignore SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
    public function processNode(Node $node, Scope $scope)
    {
        if (!$node instanceof Class_) {
            throw InvalidNodeTypeException::create($node, Class_::class);
        }

        if ($node->namespacedName === null) {
            return null;
        }

        if ($this->isFileExcluded($scope)) {
            return null;
        }

        // returns an array with class name and line - array{string, int}
        return [$node->namespacedName->toString(), $node->getLine()];
    }

    private function isFileExcluded(Scope $scope): bool
    {
        $excludedPaths = $this->configuration->getExcludedPaths();
        foreach ($excludedPaths as $path) {
            $excludePath = $this->fileHelper->absolutizePath($path);
            if (str_starts_with($scope->getFile(), $excludePath)) {
                return true;
            }
        }
        return false;
    }
}
