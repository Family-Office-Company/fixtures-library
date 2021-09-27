<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary\Tests\Support;

use FamilyOffice\FixturesLibrary\FixtureInterface;

class FixtureDependentOnFixtureWithConstructorArgument implements FixtureInterface
{
    public function getFlags(): array
    {
        return [];
    }

    public function getDependencies(): array
    {
        return [FixtureWithConstructorArgument::class];
    }

    public function load(): void
    {
    }
}
