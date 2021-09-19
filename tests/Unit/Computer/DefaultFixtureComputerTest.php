<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary\Tests\Unit\Computer;

use FamilyOffice\FixturesLibrary\Computer\DefaultFixtureComputer;
use FamilyOffice\FixturesLibrary\Tests\Support\Fixture1;
use PHPUnit\Framework\TestCase;

final class DefaultFixtureComputerTest extends TestCase
{
    public function testcomputeFixture(): void
    {
        $computer = new DefaultFixtureComputer();

        $fixture = $this->getMockBuilder(Fixture1::class)->onlyMethods(['load'])->getMock();
        $fixture->expects(self::exactly(0))->method('load');

        $computer->computeFixture($fixture);
    }
}
