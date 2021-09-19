<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary\Tests\Unit\Loader;

use FamilyOffice\FixturesLibrary\Loader\DefaultFixtureLoader;
use FamilyOffice\FixturesLibrary\Tests\Support\Fixture1;
use PHPUnit\Framework\TestCase;

final class DefaultFixtureLoaderTest extends TestCase
{
    public function testLoadFixture(): void
    {
        $fixture = $this->getMockBuilder(Fixture1::class)->onlyMethods(['load'])->getMock();
        $fixture->expects(self::once())->method('load');

        $fixtureLoader = new DefaultFixtureLoader();
        $fixtureLoader->loadFixture($fixture);
    }
}
