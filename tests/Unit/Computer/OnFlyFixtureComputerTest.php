<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary\Tests\Unit\Computer;

use FamilyOffice\FixturesLibrary\Computer\OnFlyFixtureComputer;
use FamilyOffice\FixturesLibrary\Tests\Support\Fixture1;
use PHPUnit\Framework\TestCase;

final class OnFlyFixtureComputerTest extends TestCase
{
    public function testComputeFixture(): void
    {
        $computer = new OnFlyFixtureComputer();

        $fixture = $this->getMockBuilder(Fixture1::class)->onlyMethods(['load'])->getMock();
        $fixture->expects(self::once())->method('load');

        $computer->computeFixture($fixture);
    }
}
