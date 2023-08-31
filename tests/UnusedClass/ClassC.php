<?php

declare(strict_types=1);

namespace Xact\PHPStan\Tests\UnusedClass;

use Xact\PHPStan\TestsUnusedClass\ClassB;

class ClassC
{
    protected ClassB $classB;

    public function __construct()
    {
        $this->classB = new ClassB();
    }
}
