<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary\Tests\Support;

use FamilyOffice\FixturesLibrary\FixtureFactoryInterface;
use FamilyOffice\FixturesLibrary\FixtureInterface;

class FixtureFactory implements FixtureFactoryInterface
{
    public function createInstance(string $fixtureClass): FixtureInterface
    {
        // will be mocked anyways
        return new $fixtureClass();
    }
}
