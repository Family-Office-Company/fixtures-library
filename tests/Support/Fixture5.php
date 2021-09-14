<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary\Tests\Support;

use FamilyOffice\FixturesLibrary\FixtureInterface;

final class Fixture5 implements FixtureInterface
{
    public function getDependencies(): array
    {
        return [Fixture4::class, Fixture3::class];
    }

    public function load(): void
    {
    }
}
