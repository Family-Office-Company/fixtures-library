<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary;

interface FixtureFactoryInterface
{
    public function createInstance(string $fixtureClass): FixtureInterface;
}
