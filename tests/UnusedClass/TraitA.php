<?php

declare(strict_types=1);

namespace Xact\PHPStan\Tests\UnusedClass;

trait TraitA
{
    public function testA(): int
    {
        return 1;
    }
}
