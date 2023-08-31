<?php

declare(strict_types=1);

namespace Xact\PHPStan\Collectors;

use PhpParser\Node;
use PhpParser\Node\Stmt\Use_ ;
use PHPStan\Analyser\Scope;
use PHPStan\Collectors\Collector;
use Xact\PHPStan\Exception\InvalidNodeTypeException;

class ClassUseCollector implements Collector
{
    public function getNodeType(): string
    {
        return Use_ ::class;
    }

    /**
     * @inheritDoc
     */
    // phpcs:ignore SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
    public function processNode(Node $node, Scope $scope)
    {
        if (!$node instanceof Use_) {
            throw InvalidNodeTypeException::create($node, Use_::class);
        }

        $uses = [];
        if ($node instanceof Use_ && $node->type === Use_::TYPE_NORMAL) {
            foreach ($node->uses as $use) {
                $uses[(string) $use->alias] = (string) $use->name;
            }
        }

        // returns an array with used class name
        return $uses;
    }
}
