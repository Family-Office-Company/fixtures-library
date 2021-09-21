<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary\Example\Advanced\Loader;

use FamilyOffice\FixturesLibrary\FixtureInterface;
use FamilyOffice\FixturesLibrary\FixtureLoaderInterface;
use FamilyOffice\FixturesLibrary\Loader\DefaultFixtureLoader;

final class CustomFixtureLoader implements FixtureLoaderInterface
{
    private DefaultFixtureLoader $defaultFixtureLoader;

    public function __construct()
    {
        $this->defaultFixtureLoader = new DefaultFixtureLoader();
    }

    public function loadFixture(FixtureInterface $fixture): void
    {
        echo sprintf('loading %s%s', \get_class($fixture), PHP_EOL);

        $this->defaultFixtureLoader->loadFixture($fixture);
    }
}
