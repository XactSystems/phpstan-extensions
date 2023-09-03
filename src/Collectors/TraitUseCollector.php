<?php

declare(strict_types=1);

namespace Xact\PHPStan\Collectors;

use PhpParser\Node;
use PhpParser\Node\Stmt\TraitUse;
use PhpParser\Node\Stmt\Use_ ;
use PHPStan\Analyser\Scope;
use PHPStan\Collectors\Collector;
use Xact\PHPStan\Exception\InvalidNodeTypeException;

class TraitUseCollector implements Collector
{
    public function getNodeType(): string
    {
        return TraitUse ::class;
    }

    /**
     * @inheritDoc
     */
    // phpcs:ignore SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
    public function processNode(Node $node, Scope $scope)
    {
        if (!$node instanceof TraitUse) {
            throw InvalidNodeTypeException::create($node, Use_::class);
        }

        $uses = [];
        foreach ($node->traits as $traitNodeName) {
            $traitName = $traitNodeName->toString();
            $uses[$traitName] = $traitName;
        }

        // returns an array of used trait names
        return $uses;
    }
}
