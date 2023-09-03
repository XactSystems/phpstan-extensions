<?php

declare(strict_types=1);

namespace Xact\PHPStan\Rules;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\CollectedDataNode;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use Xact\PHPStan\Collectors\ClassGroupUseCollector;
use Xact\PHPStan\Collectors\ClassUseCollector;
use Xact\PHPStan\Collectors\DeclareClassCollector;
use Xact\PHPStan\Configuration;
use Xact\PHPStan\Exception\InvalidNodeTypeException;
use Xact\PHPStan\Frameworks\Doctrine;
use Xact\PHPStan\Frameworks\PHPUnit;
use Xact\PHPStan\Frameworks\Symfony;

class UnusedClassRule implements Rule
{
    private Configuration $configuration;
    private ReflectionProvider $reflectionProvider;

    public function __construct(Configuration $configuration, ReflectionProvider $reflectionProvider)
    {
        $this->configuration = $configuration;
        $this->reflectionProvider = $reflectionProvider;
    }

    /**
     * @inheritDoc
     */
    public function getNodeType(): string
    {
        return CollectedDataNode::class;
    }

    /**
     * @inheritDoc
     */
    // phpcs:ignore SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
    public function processNode(Node $node, Scope $scope): array
    {
        if (!$node instanceof CollectedDataNode) {
            throw InvalidNodeTypeException::create($node, CollectedDataNode::class);
        }

        if ($node->isOnlyFilesAnalysis()) {
            return [];
        }

        $classDeclarationData = $node->get(DeclareClassCollector::class);
        $groupUses = $node->get(ClassGroupUseCollector::class);
        $normalUses = $node->get(ClassUseCollector::class);

        $usedClasses = [];
        foreach ($groupUses as $fileUses) {
            foreach ((array)$fileUses as $classUse) {
                foreach ((array)$classUse as $class) {
                    $usedClasses[$class] = $class;
                }
            }
        }
        foreach ($normalUses as $fileUses) {
            foreach ((array)$fileUses as $classUse) {
                foreach ((array)$classUse as $class) {
                    $usedClasses[$class] = $class;
                }
            }
        }

        $errors = [];
        foreach ($classDeclarationData as $file => $declarations) {
            /**
             * @var string $className
             * @var int $line
             */
            foreach ($declarations as [$className, $line]) {
                if ($this->isKnownFrameworkService($className)) {
                    continue;
                }

                if (!array_key_exists($className, $usedClasses)) {
                    $errors[] = RuleErrorBuilder::message("Class {$className} is never used.")
                        ->file($file)
                        ->line($line)
                        ->build();
                }
            }
        }

        return $errors;
    }

    private function isKnownFrameworkService(string $className): bool
    {
        if ($this->configuration->isExcludeFrameworks() === false && count($this->configuration->getBaseClassExcludes()) === 0) {
            return false;
        }
        if ($this->reflectionProvider->hasClass($className) === false) {
            return false;
        }

        $excludedClasses = $this->configuration->getBaseClassExcludes();
        if ($this->configuration->isExcludeFrameworks() === true) {
            $excludedClasses = array_merge(
                $excludedClasses,
                Symfony::$classes,
                Doctrine::$classes,
                PHPUnit::$classes,
            );
        }
        // Does the class name extend a known framework base class that is not directly used in client code?
        $reflection = $this->reflectionProvider->getClass($className);
        foreach ($reflection->getParentClassesNames() as $parentName) {
            if (in_array($parentName, $excludedClasses)) {
                return true;
            }
        }

        return false;
    }
}
