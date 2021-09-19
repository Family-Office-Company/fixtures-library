<?php

declare(strict_types=1);

use FamilyOffice\FixturesLibrary\Dependency\ChainBuilder;
use FamilyOffice\FixturesLibrary\Example\Advanced\Computer\CustomComputer;
use FamilyOffice\FixturesLibrary\Example\Advanced\Factory\CustomFixtureFactory;
use FamilyOffice\FixturesLibrary\Example\Advanced\Loader\CustomFixtureLoader;
use FamilyOffice\FixturesLibrary\Example\Basic\Fixtures\ElephantFixture;
use FamilyOffice\FixturesLibrary\Loader\ChainLoader;

require __DIR__ . '/../../vendor/autoload.php';

$customFixtureFactory = new CustomFixtureFactory();
$customComputer = new CustomComputer();
$chainBuilder = new ChainBuilder($customFixtureFactory, $customComputer);

echo '> building dependency chain' . PHP_EOL;
$dependencyChain = $chainBuilder->build([new ElephantFixture()]);
echo PHP_EOL;

$customFixtureLoader = new CustomFixtureLoader();
$dependencyChainLoader = new ChainLoader($customFixtureFactory, $customFixtureLoader);

echo '> loading dependency chain' . PHP_EOL;
$dependencyChainLoader->loadDependencyChain($dependencyChain);
