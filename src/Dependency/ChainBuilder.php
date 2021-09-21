<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary\Dependency;

use FamilyOffice\FixturesLibrary\Computer\DefaultFixtureComputer;
use FamilyOffice\FixturesLibrary\Computer\OnFlyFixtureComputer;
use FamilyOffice\FixturesLibrary\Exception\CircularReferenceException;
use FamilyOffice\FixturesLibrary\Exception\InvalidFixtureException;
use FamilyOffice\FixturesLibrary\Factory\DefaultFixtureFactory;
use FamilyOffice\FixturesLibrary\FixtureComputerInterface;
use FamilyOffice\FixturesLibrary\FixtureFactoryInterface;
use FamilyOffice\FixturesLibrary\FixtureInterface;

final class ChainBuilder
{
    private FixtureFactoryInterface $fixtureFactory;
    private Validator $validator;
    private FixtureComputerInterface $fixtureComputer;

    /**
     * @var string[]
     */
    private array $computed = [];

    public function __construct(FixtureFactoryInterface $fixtureFactory, FixtureComputerInterface $fixtureComputer)
    {
        $this->fixtureFactory = $fixtureFactory;
        $this->validator = new Validator();
        $this->fixtureComputer = $fixtureComputer;
    }

    public static function createDefault(): self
    {
        return new self(new DefaultFixtureFactory(), new DefaultFixtureComputer());
    }

    public static function createQuickLoader(): self
    {
        return new self(new DefaultFixtureFactory(), new OnFlyFixtureComputer());
    }

    /**
     * @param FixtureInterface[] $fixtures
     *
     * @psalm-return array<class-string, array>
     *
     * @throws InvalidFixtureException|CircularReferenceException
     */
    public function build(array $fixtures): array
    {
        $dependencyChain = [];

        // way faster than waiting for computation
        if (0 === \count($fixtures)) {
            return [];
        }

        foreach ($fixtures as $fixture) {
            $fixtureClass = \get_class($fixture);

            if ($this->alreadyComputed($fixtureClass)) {
                continue;
            }

            $this->validator->validateDependencyClass($fixtureClass);

            // saving scoped chain for circular reference detection
            $scopedChain = [$fixtureClass];
            $dependencyChain[$fixtureClass] = $this->buildDependencySubChain(
                $fixture->getDependencies(),
                $scopedChain
            );

            $this->computeFixture($fixture, $fixtureClass);
        }

        return $dependencyChain;
    }

    /**
     * @psalm-param class-string $fixtureClass
     */
    private function computeFixture(FixtureInterface $fixture, string $fixtureClass): void
    {
        $this->fixtureComputer->computeFixture($fixture);
        $this->computed[] = $fixtureClass;
    }

    /**
     * @psalm-param class-string[] $dependencyClasses
     * @psalm-param class-string[] $scopedChain
     *
     * @psalm-return array<class-string, array>
     *
     * @throws InvalidFixtureException|CircularReferenceException
     */
    private function buildDependencySubChain(array $dependencyClasses, array $scopedChain): array
    {
        $dependencySubChain = [];

        foreach ($dependencyClasses as $dependencyClass) {
            if ($this->alreadyComputed($dependencyClass)) {
                continue;
            }

            if (\in_array($dependencyClass, $scopedChain, true)) {
                throw new CircularReferenceException('Circular reference detected!');
            }

            $this->validator->validateDependencyClass($dependencyClass);
            $scopedChain[] = $dependencyClass;

            $dependency = $this->fixtureFactory->createInstance($dependencyClass);
            $dependencySubChain[$dependencyClass] = $this->buildDependencySubChain(
                $dependency->getDependencies(),
                $scopedChain
            );

            $this->computeFixture($dependency, $dependencyClass);
        }

        return $dependencySubChain;
    }

    /**
     * @psalm-param class-string $fixture
     */
    private function alreadyComputed(string $fixture): bool
    {
        return \in_array($fixture, $this->computed, true);
    }
}
