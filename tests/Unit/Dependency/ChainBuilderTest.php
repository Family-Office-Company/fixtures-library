<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary\Tests\Unit\Dependency;

use FamilyOffice\FixturesLibrary\Dependency\ChainBuilder;
use FamilyOffice\FixturesLibrary\Exception\CircularReferenceException;
use FamilyOffice\FixturesLibrary\Exception\InvalidFixtureException;
use FamilyOffice\FixturesLibrary\FixtureInterface;
use FamilyOffice\FixturesLibrary\Tests\Support\CircularReferenceFixture1;
use FamilyOffice\FixturesLibrary\Tests\Support\CircularReferenceFixture2;
use FamilyOffice\FixturesLibrary\Tests\Support\Fixture1;
use FamilyOffice\FixturesLibrary\Tests\Support\Fixture2;
use FamilyOffice\FixturesLibrary\Tests\Support\Fixture3;
use FamilyOffice\FixturesLibrary\Tests\Support\Fixture4;
use FamilyOffice\FixturesLibrary\Tests\Support\Fixture5;
use FamilyOffice\FixturesLibrary\Tests\Support\FixtureDependentOnFixtureWithConstructorArgument;
use FamilyOffice\FixturesLibrary\Tests\Support\FixtureFactory;
use FamilyOffice\FixturesLibrary\Tests\Support\InvalidFixture;
use FamilyOffice\FixturesLibrary\Tests\Support\NestedCircularReferenceFixture1;
use FamilyOffice\FixturesLibrary\Tests\Support\NestedCircularReferenceFixture2;
use FamilyOffice\FixturesLibrary\Tests\Support\NestedCircularReferenceFixture3;
use FamilyOffice\FixturesLibrary\Tests\Support\SelfDependentFixture;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class ChainBuilderTest extends TestCase
{
    /**
     * @dataProvider dataProviderTestBuild
     *
     * @psalm-param array<class-string, array> $expected
     * @param FixtureInterface[]         $fixtures
     *
     * @throws CircularReferenceException
     * @throws InvalidFixtureException
     */
    public function testBuild(array $expected, array $fixtures): void
    {
        $chainBuilder = new ChainBuilder(new FixtureFactory());

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
        $chainBuilder = new ChainBuilder(new FixtureFactory());

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
        /** @var FixtureFactory|MockObject $fixtureFactory */
        $fixtureFactory = $this->getMockBuilder(FixtureFactory::class)->onlyMethods(['createInstance'])->getMock();
        $fixtureFactory->expects(self::once())->method('createInstance');

        $chainBuilder = new ChainBuilder($fixtureFactory);
        $chainBuilder->build([new FixtureDependentOnFixtureWithConstructorArgument()]);

        self::assertTrue(true);
    }

    public function testFixturesNotValidatedOnTopLevel(): void
    {
        $fixtureFactory = new FixtureFactory();
        $chainBuilder = new ChainBuilder($fixtureFactory);

        $this->expectException(InvalidFixtureException::class);

        $chainBuilder->build([new InvalidFixture()]);
    }

    public function testNestedCircularReferenceNotDetected(): void
    {
        $fixtureFactory = new FixtureFactory();
        $chainBuilder = new ChainBuilder($fixtureFactory);

        $this->expectException(CircularReferenceException::class);

        $chainBuilder->build([new NestedCircularReferenceFixture1()]);
    }
}
