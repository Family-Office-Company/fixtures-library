<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary\Example;

use FamilyOffice\FixturesLibrary\FixtureFactoryInterface;
use FamilyOffice\FixturesLibrary\FixtureInterface;

final class MyFixtureFactory implements FixtureFactoryInterface
{
    /**
     * @psalm-param class-string $fixtureClass
     */
    public function createInstance(string $fixtureClass): FixtureInterface
    {
        return new $fixtureClass();
    }
}
