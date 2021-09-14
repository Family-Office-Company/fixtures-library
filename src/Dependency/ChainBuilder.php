<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary\Dependency;

use FamilyOffice\FixturesLibrary\Exception\CircularReferenceException;
use FamilyOffice\FixturesLibrary\Exception\InvalidFixtureException;
use FamilyOffice\FixturesLibrary\FixtureInterface;

final class ChainBuilder
{
    private Validator $validator;

    /**
     * @var string[]
     */
    private array $computed = [];

    public function __construct()
    {
        $this->validator = new Validator();
    }

    /**
     * @param FixtureInterface[] $fixtures
     *
     * @return array<array>
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

            // saving current chain for circular reference detection
            $currentChain = [$fixtureClass];
            $dependencyChain[$fixtureClass] = $this->buildDependencySubChain($fixture->getDependencies(), $currentChain);

            $this->computed[] = $fixtureClass;
        }

        return $dependencyChain;
    }

    /**
     * @param class-string[] $dependencyClasses
     * @param class-string[] $currentChain
     *
     * @return array<array>
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

            $this->validator->validateDependencyClass($dependencyClass);
            $this->computed[] = $dependencyClass;

            /** @var FixtureInterface $dependency */
            $dependency = new $dependencyClass();
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
