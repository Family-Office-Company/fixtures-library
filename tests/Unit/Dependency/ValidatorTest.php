<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary\Tests\Unit\Dependency;

use FamilyOffice\FixturesLibrary\Dependency\Validator;
use FamilyOffice\FixturesLibrary\Exception\InvalidFixtureException;
use FamilyOffice\FixturesLibrary\Tests\Support\Fixture1;
use FamilyOffice\FixturesLibrary\Tests\Support\Fixture2;
use FamilyOffice\FixturesLibrary\Tests\Support\Fixture3;
use FamilyOffice\FixturesLibrary\Tests\Support\Fixture4;
use FamilyOffice\FixturesLibrary\Tests\Support\Fixture5;
use FamilyOffice\FixturesLibrary\Tests\Support\FixtureWithConstructorArgument;
use PHPUnit\Framework\TestCase;
use Safe\Exceptions\SplException;
use Safe\Exceptions\StringsException;

final class ValidatorTest extends TestCase
{
    /**
     * @dataProvider dataProviderTestValidDependencyClasses
     *
     * @throws InvalidFixtureException
     * @throws SplException
     * @throws StringsException
     */
    public function testValidDependencyClasses(string $dependencyClass): void
    {
        $validator = new Validator();
        $validator->validateDependencyClass($dependencyClass);

        self::assertTrue(true);
    }

    public function dataProviderTestValidDependencyClasses(): iterable
    {
        yield [Fixture1::class];
        yield [Fixture2::class];
        yield [Fixture3::class];
        yield [Fixture4::class];
        yield [Fixture5::class];
    }

    /**
     * @dataProvider dataProviderTestInvalidDependencyClasses
     *
     * @throws InvalidFixtureException
     * @throws SplException
     * @throws StringsException
     */
    public function testInvalidDependencyClasses(string $dependencyClass): void
    {
        $validator = new Validator();

        $this->expectException(InvalidFixtureException::class);

        $validator->validateDependencyClass($dependencyClass);
    }

    public function dataProviderTestInvalidDependencyClasses(): iterable
    {
        yield [__CLASS__];
        yield ['test'];
        yield [\stdClass::class];
    }

    /**
     * @throws InvalidFixtureException
     * @throws SplException
     * @throws StringsException
     */
    public function testFixtureWithConstructorArgumentCrashes(): void
    {
        $validator = new Validator();
        $validator->validateDependencyClass(FixtureWithConstructorArgument::class);
        self::assertTrue(true);
    }
}
