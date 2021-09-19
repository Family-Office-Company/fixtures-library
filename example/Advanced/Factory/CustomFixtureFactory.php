<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary\Example\Advanced\Factory;

use FamilyOffice\FixturesLibrary\Factory\DefaultFixtureFactory;
use FamilyOffice\FixturesLibrary\FixtureFactoryInterface;
use FamilyOffice\FixturesLibrary\FixtureInterface;

final class CustomFixtureFactory implements FixtureFactoryInterface
{
    private DefaultFixtureFactory $defaultFixtureFactory;

    public function __construct()
    {
        $this->defaultFixtureFactory = new DefaultFixtureFactory();
    }

    public function createInstance(string $fixtureClass): FixtureInterface
    {
        echo sprintf('creating instance of %s%s', $fixtureClass, PHP_EOL);

        return $this->defaultFixtureFactory->createInstance($fixtureClass);
    }
}
