<?php

declare(strict_types=1);

namespace Xact\PHPStan\Tests;

use PHPStan\Rules\Rule;
use PHPStan\Rules\Traits\TraitDeclarationCollector;
use PHPStan\Rules\Traits\TraitUseCollector;
use PHPStan\Testing\RuleTestCase;
use Xact\PHPStan\Rules\UnusedTraitRule;

/**
 * @extends RuleTestCase<\Xact\PHPStan\Rules\UnusedTraitRule>
 */
class UnusedTraitTest extends RuleTestCase
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
        $this->analyse([__DIR__ . '/UnusedClass/ClassA.php', __DIR__ . '/UnusedClass/TraitA.php', __DIR__ . '/UnusedClass/TraitB.php'], [
            [
                'Trait Xact\PHPStan\Tests\UnusedClass\TraitB is never used.', // asserted error message
                7, // asserted error line
            ],
        ]);

        // the test fails, if the expected error does not occur,
        // or if there are other errors reported beside the expected one
    }

    protected function getRule(): Rule
    {
        // getRule() method needs to return an instance of the tested rule
        return new UnusedTraitRule();
    }

    /**
     * @return object[]
     */
    protected function getCollectors(): array
    {
        return [
            self::getContainer()->getByType(TraitDeclarationCollector::class),
            self::getContainer()->getByType(TraitUseCollector::class),
        ];
    }
}
