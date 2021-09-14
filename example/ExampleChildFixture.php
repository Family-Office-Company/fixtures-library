<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary\Example;

use FamilyOffice\FixturesLibrary\FixtureInterface;

final class ExampleChildFixture implements FixtureInterface
{
    public function getDependencies(): array
    {
        return [];
    }

    public function load(): void
    {
        echo 'child!' . PHP_EOL;
    }
}
