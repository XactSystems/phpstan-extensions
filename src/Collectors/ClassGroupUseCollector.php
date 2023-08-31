<?php

declare(strict_types=1);

namespace Xact\PHPStan\Collectors;

use PhpParser\Node;
use PhpParser\Node\Stmt\GroupUse;
use PhpParser\Node\Stmt\Use_;
use PHPStan\Analyser\Scope;
use PHPStan\Collectors\Collector;
use Xact\PHPStan\Exception\InvalidNodeTypeException;

class ClassGroupUseCollector implements Collector
{
    public function getNodeType(): string
    {
        return GroupUse ::class;
    }

    /**
     * @inheritDoc
     */
    // phpcs:ignore SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
    public function processNode(Node $node, Scope $scope)
    {
        if (!$node instanceof GroupUse) {
            throw InvalidNodeTypeException::create($node, GroupUse::class);
        }

        $uses = [];
        $prefix = (string) $node->prefix;
        foreach ($node->uses as $use) {
            if ($node->type === Use_::TYPE_NORMAL || $use->type === Use_::TYPE_NORMAL) {
                $uses[(string)$use->alias] = sprintf('%s\\%s', $prefix, $use->name);
            }
        }

        // returns an array with used class name
        return $uses;
    }
}
