<?php

declare(strict_types=1);

use FamilyOffice\FixturesLibrary\Dependency\ChainBuilder;
use FamilyOffice\FixturesLibrary\Example\ExampleParentFixture;
use FamilyOffice\FixturesLibrary\Example\MyFixtureFactory;
use FamilyOffice\FixturesLibrary\Fixture\Loader;

require __DIR__ . '/../vendor/autoload.php';

$chainBuilder = new ChainBuilder();

$fixtures = [new ExampleParentFixture()/* ... */];

$dependencyChain = $chainBuilder->build($fixtures);

$fixtureFactory = new MyFixtureFactory();

$fixtureLoader = new Loader($fixtureFactory);
$fixtureLoader->loadDependencyChain($dependencyChain);
