<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary;

interface FixtureInterface
{
    /**
     * @return class-string[]
     */
    public function getDependencies(): array;

    public function load(): void;
}
