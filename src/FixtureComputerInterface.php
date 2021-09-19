<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary;

interface FixtureComputerInterface
{
    public function computeFixture(FixtureInterface $fixture): void;
}
