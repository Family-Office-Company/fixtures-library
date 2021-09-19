<?php

declare(strict_types=1);

use FamilyOffice\FixturesLibrary\Dependency\ChainBuilder;
use FamilyOffice\FixturesLibrary\Example\Basic\Fixtures\ElephantFixture;

require __DIR__ . '/../../vendor/autoload.php';

$chainBuilder = ChainBuilder::createQuickLoader();
var_dump($chainBuilder->build([new ElephantFixture()]));
