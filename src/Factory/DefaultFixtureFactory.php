<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary\Factory;

use FamilyOffice\FixturesLibrary\Exception\InvalidFixtureException;
use FamilyOffice\FixturesLibrary\FixtureFactoryInterface;
use FamilyOffice\FixturesLibrary\FixtureInterface;
use Safe\Exceptions\StringsException;

final class DefaultFixtureFactory implements FixtureFactoryInterface
{
    /**
     * @throws InvalidFixtureException
     * @throws StringsException
     */
    public function createInstance(string $fixtureClass): FixtureInterface
    {
        $instance = new $fixtureClass();

        if (!$instance instanceof FixtureInterface) {
            throw new InvalidFixtureException(\Safe\sprintf('%s is not a valid fixture!', $fixtureClass));
        }

        return $instance;
    }
}
