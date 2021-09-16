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

            // saving current chain for circular reference detection
            $currentChain = [$fixtureClass];
            $dependencyChain[$fixtureClass] = $this->buildDependencySubChain(
                $fixture->getDependencies(),
                $currentChain
            );

            $this->computed[] = $fixtureClass;
        }

        return $dependencyChain;
    }

    /**
     * @psalm-param class-string[] $dependencyClasses
     * @psalm-param class-string[] $currentChain
     *
     * @psalm-return array<class-string, array>
     *
     * @throws InvalidFixtureException|CircularReferenceException
     */
    private function buildDependencySubChain(array $dependencyClasses, array $currentChain): array
    {
        $dependencySubChain = [];

        foreach ($dependencyClasses as $dependencyClass) {
            if ($this->alreadyComputed($dependencyClass)) {
                continue;
            }

            if (\in_array($dependencyClass, $currentChain, true)) {
                throw new CircularReferenceException('Circular reference detected!');
            }

            /* @infection-ignore-all */
            $this->validator->validateDependencyClass($dependencyClass);
            $this->computed[] = $dependencyClass;

            $dependency = $this->fixtureFactory->createInstance($dependencyClass);
            $dependencySubChain[$dependencyClass] = $this->buildDependencySubChain(
                $dependency->getDependencies(),
                $currentChain
            );
        }

        return $dependencySubChain;
    }

    private function alreadyComputed(string $fixture): bool
    {
        return \in_array($fixture, $this->computed, true);
    }
}
