<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary;

interface FixtureFactoryInterface
{
    /**
     * @param class-string $fixtureClass
     */
    public function createInstance(string $fixtureClass): FixtureInterface;
}
