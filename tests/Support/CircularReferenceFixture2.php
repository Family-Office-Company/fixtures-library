<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary\Tests\Support;

use FamilyOffice\FixturesLibrary\FixtureInterface;

final class CircularReferenceFixture2 implements FixtureInterface
{
    public function getDependencies(): array
    {
        return [CircularReferenceFixture1::class];
    }

    public function load(): void
    {
    }
}
