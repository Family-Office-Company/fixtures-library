<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary\Example\Basic\Fixtures;

use FamilyOffice\FixturesLibrary\FixtureInterface;

final class EarFixture implements FixtureInterface
{
    public function getDependencies(): array
    {
        return [];
    }

    public function load(): void
    {
    }
}
