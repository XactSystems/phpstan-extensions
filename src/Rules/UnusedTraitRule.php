<?php

declare(strict_types=1);

namespace Xact\PHPStan\Rules;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\CollectedDataNode;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Rules\Traits\TraitDeclarationCollector;
use PHPStan\Rules\Traits\TraitUseCollector;
use Xact\PHPStan\Exception\InvalidNodeTypeException;

class UnusedTraitRule implements Rule
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

        $traitDeclarationData = $node->get(TraitDeclarationCollector::class);
        $traitUses = $node->get(TraitUseCollector::class);

        $usedTraits = [];
        foreach ($traitUses as $fileUses) {
            foreach ((array)$fileUses as $traitUse) {
                foreach ((array)$traitUse as $trait) {
                    $usedTraits[$trait] = $trait;
                }
            }
        }

        $errors = [];
        foreach ($traitDeclarationData as $file => $declarations) {
            /**
             * @var string $className
             * @var int $line
             */
            foreach ($declarations as [$className, $line]) {
                if (!array_key_exists($className, $usedTraits)) {
                    $errors[] = RuleErrorBuilder::message("Trait {$className} is never used.")
                        ->file($file)
                        ->line($line)
                        ->build();
                }
            }
        }

        return $errors;
    }
}
