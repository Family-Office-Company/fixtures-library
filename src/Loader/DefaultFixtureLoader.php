<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary\Loader;

use FamilyOffice\FixturesLibrary\FixtureInterface;
use FamilyOffice\FixturesLibrary\FixtureLoaderInterface;

final class DefaultFixtureLoader implements FixtureLoaderInterface
{
    public function loadFixture(FixtureInterface $fixture): void
    {
        $fixture->load();
    }
}
