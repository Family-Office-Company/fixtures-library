<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary\Tests\Support;

use FamilyOffice\FixturesLibrary\FixtureInterface;

class Fixture2 implements FixtureInterface
{
    public function getDependencies(): array
    {
        return [Fixture4::class];
    }

    public function load(): void
    {
    }
}
