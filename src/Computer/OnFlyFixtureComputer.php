<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary\Computer;

use FamilyOffice\FixturesLibrary\FixtureComputerInterface;
use FamilyOffice\FixturesLibrary\FixtureInterface;

final class OnFlyFixtureComputer implements FixtureComputerInterface
{
    public function computeFixture(FixtureInterface $fixture): void
    {
        $fixture->load();
    }
}
