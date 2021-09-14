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
use PHPUnit\Framework\TestCase;

final class ValidatorTest extends TestCase
{
    /**
     * @dataProvider dataProviderTestValidDependencyClasses
     *
     * @param class-string $dependencyClass
     *
     * @throws InvalidFixtureException
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
     * @param class-string $dependencyClass
     *
     * @throws InvalidFixtureException
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
}
