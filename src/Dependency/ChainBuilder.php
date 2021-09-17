<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary\Dependency;

use FamilyOffice\FixturesLibrary\Exception\CircularReferenceException;
use FamilyOffice\FixturesLibrary\Exception\InvalidFixtureException;
use FamilyOffice\FixturesLibrary\FixtureFactoryInterface;
use FamilyOffice\FixturesLibrary\FixtureInterface;

final class ChainBuilder
{
    private FixtureFactoryInterface $fixtureFactory;
    private Validator $validator;

    /**
     * @var string[]
     */
    private array $computed = [];

    public function __construct(FixtureFactoryInterface $fixtureFactory)
    {
        $this->fixtureFactory = $fixtureFactory;
        $this->validator = new Validator();
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
                /* @infection-ignore-all */
                continue;
            }

            /* @infection-ignore-all */
            $this->validator->validateDependencyClass($fixtureClass);

            // saving scoped chain for circular reference detection
            // infection-ignored because not adding class to scoped chain here
            // would result in a superfluous depth level
            /* @infection-ignore-all */
            $scopedChain = [$fixtureClass];
            $dependencyChain[$fixtureClass] = $this->buildDependencySubChain(
                $fixture->getDependencies(),
                $scopedChain
            );

            $this->computed[] = $fixtureClass;
        }

        return $dependencyChain;
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

            /* @infection-ignore-all */
            $this->validator->validateDependencyClass($dependencyClass);
            $scopedChain[] = $dependencyClass;

            $dependency = $this->fixtureFactory->createInstance($dependencyClass);
            $dependencySubChain[$dependencyClass] = $this->buildDependencySubChain(
                $dependency->getDependencies(),
                $scopedChain
            );

            $this->computed[] = $dependencyClass;
        }

        return $dependencySubChain;
    }

    private function alreadyComputed(string $fixture): bool
    {
        return \in_array($fixture, $this->computed, true);
    }
}
