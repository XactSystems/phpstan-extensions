<?php

declare(strict_types=1);

namespace Xact\PHPStan\Rules;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\CollectedDataNode;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use Xact\PHPStan\Collectors\ClassGroupUseCollector;
use Xact\PHPStan\Collectors\ClassUseCollector;
use Xact\PHPStan\Collectors\DeclareClassCollector;
use Xact\PHPStan\Exception\InvalidNodeTypeException;

class UnusedClassRule implements Rule
{
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
}
