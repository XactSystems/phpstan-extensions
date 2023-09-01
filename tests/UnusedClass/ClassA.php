<?php

declare(strict_types=1);

namespace Xact\PHPStan\Tests\UnusedClass;

use Xact\PHPStan\Tests\UnusedClass\TraitA;

class ClassA
{
    use TraitA;

    public function __construct()
    {
        $this->testA();
    }
}
