<?php

declare(strict_types=1);

namespace Xact\PHPStan\Frameworks;

final class Symfony
{
    /** @var string[] */
    public static array $classes = [
        'Symfony\Bundle\FrameworkBundle\Controller\AbstractController',
        'Symfony\Component\Console\Command\Command',
        'Symfony\Component\HttpKernel\Bundle\Bundle',
        'Symfony\Component\HttpKernel\Kernel',
        'Symfony\Component\Validator\Constraint',
        'Symfony\Component\Validator\ConstraintValidator',
        'Twig\Extension\AbstractExtension',
    ];
}
