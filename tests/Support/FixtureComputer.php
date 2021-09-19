<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary\Tests\Support;

use FamilyOffice\FixturesLibrary\FixtureComputerInterface;
use FamilyOffice\FixturesLibrary\FixtureInterface;

class FixtureComputer implements FixtureComputerInterface
{
    public function computeFixture(FixtureInterface $fixture): void
    {
    }
}
