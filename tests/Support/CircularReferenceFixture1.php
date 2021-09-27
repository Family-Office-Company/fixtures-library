<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary\Tests\Support;

use FamilyOffice\FixturesLibrary\FixtureInterface;

class CircularReferenceFixture1 implements FixtureInterface
{
    public function getFlags(): array
    {
        return [];
    }

    public function getDependencies(): array
    {
        return [CircularReferenceFixture2::class];
    }

    public function load(): void
    {
    }
}
