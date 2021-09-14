<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary\Dependency;

use FamilyOffice\FixturesLibrary\Exception\InvalidFixtureException;
use FamilyOffice\FixturesLibrary\FixtureInterface;

final class Validator
{
    /**
     * @param mixed $dependencyClass
     *
     * @throws InvalidFixtureException
     */
    public function validateDependencyClass($dependencyClass): void
    {
        if (\is_string($dependencyClass)
            && class_exists($dependencyClass)
            && false !== ($implements = class_implements($dependencyClass))
            && \in_array(FixtureInterface::class, $implements, true)) {
            return;
        }

        throw new InvalidFixtureException(sprintf('%s is not a valid fixture dependency!', $dependencyClass));
    }
}
