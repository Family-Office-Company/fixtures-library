<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary\Example\Basic\Fixtures;

use FamilyOffice\FixturesLibrary\FixtureInterface;

final class ElephantFixture implements FixtureInterface
{
    public function getFlags(): array
    {
        return [];
    }

    public function getDependencies(): array
    {
        return [EarFixture::class];
    }

    public function load(): void
    {
    }
}
