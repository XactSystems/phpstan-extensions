<?php

declare(strict_types=1);

namespace Xact\PHPStan\Exception;

use Exception;

class InvalidNodeTypeException extends Exception
{
    public static function create(object $node, string $expected): self
    {
        $type = get_class($node);
        return new self("Expected node type {$expected} but found {$type}.");
    }
}
