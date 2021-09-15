<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary\Tests\Support;

use FamilyOffice\FixturesLibrary\FixtureInterface;

class Fixture3 implements FixtureInterface
{
    public function getDependencies(): array
    {
        return [Fixture1::class];
    }

    public function load(): void
    {
    }
}
