<?php

declare(strict_types=1);

namespace Xact\PHPStan\Collectors;

use PhpParser\Node;
use PhpParser\Node\Stmt\Trait_;
use PHPStan\Analyser\Scope;
use PHPStan\Collectors\Collector;
use PHPStan\File\FileHelper;
use Xact\PHPStan\Configuration;
use Xact\PHPStan\Exception\InvalidNodeTypeException;

class DeclareTraitCollector implements Collector
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
        return Trait_::class;
    }

    /**
     * @inheritDoc
     */
    // phpcs:ignore SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
    public function processNode(Node $node, Scope $scope)
    {
        if (!$node instanceof Trait_) {
            throw InvalidNodeTypeException::create($node, Trait_::class);
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
        if ($this->configuration->isUnusedTraitsEnabled() === false) {
            return true;
        }

        $excludePaths = $this->configuration->getExcludePaths();
        foreach ($excludePaths as $path) {
            $excludePath = $this->fileHelper->absolutizePath($path);
            if (str_starts_with($scope->getFile(), $excludePath)) {
                return true;
            }
        }
        return false;
    }
}
