<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary\Example;

use FamilyOffice\FixturesLibrary\FixtureInterface;

final class ExampleParentFixture implements FixtureInterface
{
    public function getDependencies(): array
    {
        return [ExampleChildFixture::class];
    }

    public function load(): void
    {
        echo 'parent!' . PHP_EOL;
    }
}
