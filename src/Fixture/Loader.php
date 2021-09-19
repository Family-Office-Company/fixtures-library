<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary\Fixture;

use FamilyOffice\FixturesLibrary\FixtureFactoryInterface;

final class Loader
{
    private FixtureFactoryInterface $fixtureFactory;

    public function __construct(FixtureFactoryInterface $fixtureFactory)
    {
        $this->fixtureFactory = $fixtureFactory;
    }

    /**
     * @psalm-param array<class-string, array> $dependencyChain
     */
    public function loadDependencyChain(array $dependencyChain): void
    {
        /** @psalm-var array<class-string, array> $dependencySubChain */
        foreach ($dependencyChain as $parentFixture => $dependencySubChain) {
            $this->loadDependencyChain($dependencySubChain);
            $instance = $this->fixtureFactory->createInstance($parentFixture);
            $instance->load();
        }
    }
}
