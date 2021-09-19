<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary;

interface FixtureLoaderInterface
{
    public function loadFixture(FixtureInterface $fixture): void;
}
