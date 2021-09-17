<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary\Tests\Support;

use FamilyOffice\FixturesLibrary\FixtureInterface;

class NestedCircularReferenceFixture3 implements FixtureInterface
{
    public function getDependencies(): array
    {
        return [NestedCircularReferenceFixture2::class];
    }

    public function load(): void
    {
    }
}
