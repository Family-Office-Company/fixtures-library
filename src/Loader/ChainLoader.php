<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary\Loader;

use FamilyOffice\FixturesLibrary\Factory\DefaultFixtureFactory;
use FamilyOffice\FixturesLibrary\FixtureFactoryInterface;
use FamilyOffice\FixturesLibrary\FixtureLoaderInterface;

final class ChainLoader
{
    private FixtureFactoryInterface $fixtureFactory;
    private FixtureLoaderInterface $fixtureLoader;

    public function __construct(FixtureFactoryInterface $fixtureFactory, FixtureLoaderInterface $fixtureLoader)
    {
        $this->fixtureFactory = $fixtureFactory;
        $this->fixtureLoader = $fixtureLoader;
    }

    public static function createDefault(): self
    {
        return new self(new DefaultFixtureFactory(), new DefaultFixtureLoader());
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
            $this->fixtureLoader->loadFixture($instance);
        }
    }
}
