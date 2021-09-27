<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary\Tests\Support;

use FamilyOffice\FixturesLibrary\FixtureInterface;

class FixtureWithConstructorArgument implements FixtureInterface
{
    private string $foo;

    public function __construct(string $foo)
    {
        $this->foo = $foo;
    }

    public function getFlags(): array
    {
        return [];
    }

    public function getDependencies(): array
    {
        return [$this->foo];
    }

    public function load(): void
    {
    }
}
