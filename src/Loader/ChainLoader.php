<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary\Loader;

use FamilyOffice\FixturesLibrary\Factory\DefaultFixtureFactory;
use FamilyOffice\FixturesLibrary\FixtureFactoryInterface;
use FamilyOffice\FixturesLibrary\FixtureInterface;
use FamilyOffice\FixturesLibrary\FixtureLoaderInterface;
use FamilyOffice\FixturesLibrary\Flag\FixtureFlagInterface;
use Spatie\Async\Pool;

final class ChainLoader
{
    private FixtureFactoryInterface $fixtureFactory;
    private FixtureLoaderInterface $fixtureLoader;
    private Pool $pool;
    private bool $waitForPool = false;

    public function __construct(FixtureFactoryInterface $fixtureFactory, FixtureLoaderInterface $fixtureLoader)
    {
        $this->fixtureFactory = $fixtureFactory;
        $this->fixtureLoader = $fixtureLoader;
    }

    public static function createDefault(): self
    {
        return new self(new DefaultFixtureFactory(), new DefaultFixtureLoader());
    }

    public function loadDependencyChain(array $dependencyChain): void
    {
        foreach ($dependencyChain as $parentFixture => $dependencySubChain) {
            $instance = $this->fixtureFactory->createInstance($parentFixture);

            if (!\in_array(FixtureFlagInterface::FLAG_ASYNC, $instance->getFlags(), true)) {
                $this->loadFixture($dependencySubChain, $instance);

                continue;
            }

            if (!$this->waitForPool) {
                $this->pool = Pool::create();
                $this->waitForPool = true;
            }

            $this->pool->add(function () use ($dependencySubChain, $instance): void {
                $this->loadFixture($dependencySubChain, $instance);
            });
        }
    }

    private function loadFixture(array $dependencySubChain, FixtureInterface $instance): void
    {
        $this->loadDependencyChain($dependencySubChain);
        $this->fixtureLoader->loadFixture($instance);
    }

    public function __destruct()
    {
        if ($this->waitForPool) {
            $this->pool->wait();
        }
    }
}
