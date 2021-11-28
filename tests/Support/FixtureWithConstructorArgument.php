<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary\Tests\Support;

use FamilyOffice\FixturesLibrary\FixtureInterface;

class FixtureWithConstructorArgument implements FixtureInterface
{
    public function __construct(private string $foo)
    {
    }

    public function getDependencies(): array
    {
        return [$this->foo];
    }

    public function load(): void
    {
    }
}
