<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary\Dependency;

use FamilyOffice\FixturesLibrary\Exception\InvalidFixtureException;
use FamilyOffice\FixturesLibrary\FixtureInterface;
use Safe\Exceptions\SplException;
use Safe\Exceptions\StringsException;

final class Validator
{
    /**
     * @param mixed $dependencyClass
     *
     * @throws InvalidFixtureException
     * @throws SplException
     * @throws StringsException
     */
    public function validateDependencyClass($dependencyClass): void
    {
        if (\is_string($dependencyClass)
            && class_exists($dependencyClass)
            && \in_array(FixtureInterface::class, \Safe\class_implements($dependencyClass), true)) {
            return;
        }

        throw new InvalidFixtureException(\Safe\sprintf('%s is not a valid fixture!', $dependencyClass));
    }
}
