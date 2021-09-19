<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary\Example\Advanced\Computer;

use FamilyOffice\FixturesLibrary\FixtureComputerInterface;
use FamilyOffice\FixturesLibrary\FixtureInterface;

final class CustomComputer implements FixtureComputerInterface
{
    public function computeFixture(FixtureInterface $fixture): void
    {
        echo sprintf('computing %s%s', \get_class($fixture), PHP_EOL);
    }
}
