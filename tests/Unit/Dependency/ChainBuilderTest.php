<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary\Tests\Unit\Dependency;

use FamilyOffice\FixturesLibrary\Computer\DefaultFixtureComputer;
use FamilyOffice\FixturesLibrary\Computer\OnFlyFixtureComputer;
use FamilyOffice\FixturesLibrary\Dependency\ChainBuilder;
use FamilyOffice\FixturesLibrary\Exception\CircularReferenceException;
use FamilyOffice\FixturesLibrary\Exception\InvalidFixtureException;
use FamilyOffice\FixturesLibrary\Factory\DefaultFixtureFactory;
use FamilyOffice\FixturesLibrary\FixtureInterface;
use FamilyOffice\FixturesLibrary\Tests\Support\CircularReferenceFixture1;
use FamilyOffice\FixturesLibrary\Tests\Support\CircularReferenceFixture2;
use FamilyOffice\FixturesLibrary\Tests\Support\Fixture1;
use FamilyOffice\FixturesLibrary\Tests\Support\Fixture2;
use FamilyOffice\FixturesLibrary\Tests\Support\Fixture3;
use FamilyOffice\FixturesLibrary\Tests\Support\Fixture4;
use FamilyOffice\FixturesLibrary\Tests\Support\Fixture5;
use FamilyOffice\FixturesLibrary\Tests\Support\FixtureComputer;
use FamilyOffice\FixturesLibrary\Tests\Support\FixtureDependentOnFixtureWithConstructorArgument;
use FamilyOffice\FixturesLibrary\Tests\Support\FixtureFactory;
use FamilyOffice\FixturesLibrary\Tests\Support\InvalidDependencyFixture;
use FamilyOffice\FixturesLibrary\Tests\Support\InvalidFixture;
use FamilyOffice\FixturesLibrary\Tests\Support\NestedCircularReferenceFixture1;
use FamilyOffice\FixturesLibrary\Tests\Support\NestedCircularReferenceFixture2;
use FamilyOffice\FixturesLibrary\Tests\Support\NestedCircularReferenceFixture3;
use FamilyOffice\FixturesLibrary\Tests\Support\SelfDependentFixture;
use FamilyOffice\FixturesLibrary\Tests\Support\UnrelatedFixture;
use PHPUnit\Framework\TestCase;

final class ChainBuilderTest extends TestCase
{
    /**
     * @dataProvider dataProviderTestBuild
     *
     * @psalm-param array<class-string, array> $expected
     *
     * @param FixtureInterface[] $fixtures
     *
     * @throws CircularReferenceException
     * @throws InvalidFixtureException
     */
    public function testBuild(array $expected, array $fixtures): void
    {
        $chainBuilder = ChainBuilder::createDefault();

        self::assertSame($expected, $chainBuilder->build($fixtures));
    }

    public function dataProviderTestBuild(): iterable
    {
        yield [
            [
                Fixture1::class => [
                    Fixture2::class => [
                        Fixture4::class => [],
                    ],
                ],
            ],
            [new Fixture1()],
        ];

        yield [
            [
                Fixture5::class => [
                    Fixture4::class => [],
                    Fixture3::class => [
                        Fixture1::class => [
                            Fixture2::class => [],
                        ],
                    ],
                ],
            ],
            [new Fixture5()],
        ];

        yield [
            [
                Fixture5::class => [
                    Fixture4::class => [],
                    Fixture3::class => [
                        Fixture1::class => [
                            Fixture2::class => [],
                        ],
                    ],
                ],
            ],
            [new Fixture5(), new Fixture5()],
        ];

        yield [
            [
                Fixture4::class => [],
                Fixture5::class => [
                    Fixture3::class => [
                        Fixture1::class => [
                            Fixture2::class => [],
                        ],
                    ],
                ],
            ],
            [new Fixture4(), new Fixture5()],
        ];

        yield [
            [
                Fixture1::class => [
                    Fixture2::class => [
                        Fixture4::class => [],
                    ],
                ],
                UnrelatedFixture::class => [],
            ],
            [new Fixture1(), new Fixture1(), new UnrelatedFixture()],
        ];

        yield [[], []];
    }

    /**
     * @dataProvider dataProviderTestBuildCircularReferenceException
     *
     * @param FixtureInterface[] $fixtures
     *
     * @throws CircularReferenceException
     * @throws InvalidFixtureException
     */
    public function testBuildCircularReferenceException(array $fixtures): void
    {
        $chainBuilder = ChainBuilder::createDefault();

        $this->expectException(CircularReferenceException::class);

        $chainBuilder->build($fixtures);
    }

    public function dataProviderTestBuildCircularReferenceException(): iterable
    {
        yield [[new CircularReferenceFixture1()]];
        yield [[new CircularReferenceFixture1(), new CircularReferenceFixture2()]];
        yield [[new CircularReferenceFixture2()]];
        yield [[new NestedCircularReferenceFixture1()]];
        yield [[new NestedCircularReferenceFixture2()]];
        yield [[new NestedCircularReferenceFixture3()]];
        yield [[new SelfDependentFixture()]];
    }

    public function testDependencyWithConstructorArgumentCrashes(): void
    {
        $fixtureFactory = $this->getMockBuilder(FixtureFactory::class)->onlyMethods(['createInstance'])->getMock();
        $fixtureFactory->expects(self::once())->method('createInstance');

        $chainBuilder = new ChainBuilder($fixtureFactory, new DefaultFixtureComputer());
        $chainBuilder->build([new FixtureDependentOnFixtureWithConstructorArgument()]);

        self::assertTrue(true);
    }

    public function testFixturesNotValidatedOnTopLevel(): void
    {
        $chainBuilder = ChainBuilder::createDefault();

        $this->expectException(InvalidFixtureException::class);

        $chainBuilder->build([new InvalidFixture()]);
    }

    public function testNestedCircularReferenceNotDetected(): void
    {
        $chainBuilder = ChainBuilder::createDefault();

        $this->expectException(CircularReferenceException::class);

        $chainBuilder->build([new NestedCircularReferenceFixture1()]);
    }

    public function testCreateDefault(): void
    {
        $expected = new ChainBuilder(new DefaultFixtureFactory(), new DefaultFixtureComputer());
        $actual = ChainBuilder::createDefault();

        self::assertEquals($expected, $actual);
    }

    public function testCreateQuickLoader(): void
    {
        $expected = new ChainBuilder(new DefaultFixtureFactory(), new OnFlyFixtureComputer());
        $actual = ChainBuilder::createQuickLoader();

        self::assertEquals($expected, $actual);
    }

    public function testComputeFixtureCalled(): void
    {
        $fixtureComputer = $this->getMockBuilder(FixtureComputer::class)->onlyMethods(['computeFixture'])->getMock();
        $fixtureComputer->expects(self::exactly(3))->method('computeFixture');

        $chainBuilder = new ChainBuilder(new FixtureFactory(), $fixtureComputer);
        $chainBuilder->build([new Fixture1()]);
    }

    public function testInvalidDependency(): void
    {
        $chainBuilder = new ChainBuilder(new FixtureFactory(), new FixtureComputer());

        $this->expectException(InvalidFixtureException::class);

        $chainBuilder->build([new InvalidDependencyFixture()]);
    }

    public function testCircularReferenceDetectedOnFirstLevel(): void
    {
        $fixtureFactory = $this->getMockBuilder(FixtureFactory::class)->onlyMethods(['createInstance'])->getMock();
        $fixtureFactory->expects(self::exactly(0))->method('createInstance');

        $this->expectException(CircularReferenceException::class);

        $chainBuilder = new ChainBuilder($fixtureFactory, new FixtureComputer());
        $chainBuilder->build([new SelfDependentFixture()]);
    }
}
