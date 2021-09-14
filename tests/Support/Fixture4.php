<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary\Tests\Support;

use FamilyOffice\FixturesLibrary\FixtureInterface;

final class Fixture4 implements FixtureInterface
{
    public function getDependencies(): array
    {
        return [];
    }

    public function load(): void
    {
    }
}
