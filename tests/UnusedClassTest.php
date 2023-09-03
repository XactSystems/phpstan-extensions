<?php

declare(strict_types=1);

namespace Xact\PHPStan\Tests;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Xact\PHPStan\Collectors\ClassGroupUseCollector;
use Xact\PHPStan\Collectors\ClassUseCollector;
use Xact\PHPStan\Collectors\DeclareClassCollector;
use Xact\PHPStan\Rules\UnusedClassRule;

/**
 * @extends RuleTestCase<\Xact\PHPStan\Rules\UnusedClassRule>
 */
class UnusedClassTest extends RuleTestCase
{
    /**
     * @return string[]
     */
    public static function getAdditionalConfigFiles(): array
    {
        // path to your project's phpstan.neon, or extension.neon in case of custom extension packages
        // this is only necessary if your custom rule relies on some extra configuration and other extensions
        return [dirname(__DIR__) . '/config/extension.neon'];
    }

    public function testRule(): void
    {
        // first argument: path to the example file that contains some errors that should be reported by UnusedClassRule
        // second argument: an array of expected errors,
        // each error consists of the asserted error message, and the asserted error file line
        $this->analyse(
            [__DIR__ . '/UnusedClass/ClassA.php', __DIR__ . '/UnusedClass/ClassB.php', __DIR__ . '/UnusedClass/ClassC.php'],
            [
                [
                    'Class Xact\PHPStan\Tests\UnusedClass\ClassA is never used.', // asserted error message
                    9, // asserted error line
                ],
                [
                    'Class Xact\PHPStan\Tests\UnusedClass\ClassC is never used.', // asserted error message
                    9, // asserted error line
                ],
            ]
        );

        // the test fails, if the expected error does not occur,
        // or if there are other errors reported beside the expected one
    }

    protected function getRule(): Rule
    {
        // getRule() method needs to return an instance of the tested rule
        return self::getContainer()->getByType(UnusedClassRule::class);
    }

    /**
     * @return object[]
     */
    protected function getCollectors(): array
    {
        return [
            self::getContainer()->getByType(DeclareClassCollector::class),
            self::getContainer()->getByType(ClassUseCollector::class),
            self::getContainer()->getByType(ClassGroupUseCollector::class),
        ];
    }
}
