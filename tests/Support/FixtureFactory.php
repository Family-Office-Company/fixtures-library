<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary\Tests\Support;

use FamilyOffice\FixturesLibrary\FixtureFactoryInterface;
use FamilyOffice\FixturesLibrary\FixtureInterface;

final class FixtureFactory implements FixtureFactoryInterface
{
    /**
     * @param class-string $fixtureClass
     */
    public function createInstance(string $fixtureClass): FixtureInterface
    {
        // will be mocked anyways
        return new Fixture1();
    }
}
