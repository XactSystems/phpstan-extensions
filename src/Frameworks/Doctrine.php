<?php

declare(strict_types=1);

namespace Xact\PHPStan\Frameworks;

final class Doctrine
{
    /** @var string[] */
    public static array $classes = [
        'Doctrine\Common\DataFixtures\AbstractFixture',
        'Doctrine\DBAL\Types\Type',
        'Doctrine\ORM\Query\AST\Node',
    ];
}
