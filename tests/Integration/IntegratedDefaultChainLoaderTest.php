<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary\Tests\Integration;

use FamilyOffice\FixturesLibrary\Dependency\ChainBuilder;
use FamilyOffice\FixturesLibrary\Exception\CircularReferenceException;
use FamilyOffice\FixturesLibrary\Exception\InvalidFixtureException;
use FamilyOffice\FixturesLibrary\FixtureInterface;
use FamilyOffice\FixturesLibrary\Loader\ChainLoader;
use FamilyOffice\FixturesLibrary\Tests\Support\Fixture1;
use FamilyOffice\FixturesLibrary\Tests\Support\Fixture2;
use FamilyOffice\FixturesLibrary\Tests\Support\Fixture3;
use FamilyOffice\FixturesLibrary\Tests\Support\Fixture4;
use FamilyOffice\FixturesLibrary\Tests\Support\Fixture5;
use PHPUnit\Framework\TestCase;

final class IntegratedDefaultChainLoaderTest extends TestCase
{
    /**
     * @dataProvider dataProviderTestBuildAndLoadDependencyChain
     *
     * @throws CircularReferenceException
     * @throws InvalidFixtureException
     */
    public function testBuildAndLoadDependencyChain(FixtureInterface $fixture): void
    {
        $chainBuilder = ChainBuilder::createDefault();
        $dependencyChain = $chainBuilder->build([$fixture]);

        $fixtureLoader = ChainLoader::createDefault();
        $fixtureLoader->loadDependencyChain($dependencyChain);

        self::assertTrue(true);
    }

    public function dataProviderTestBuildAndLoadDependencyChain(): iterable
    {
        yield [new Fixture1()];
        yield [new Fixture2()];
        yield [new Fixture3()];
        yield [new Fixture4()];
        yield [new Fixture5()];
    }
}
