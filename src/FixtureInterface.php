<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary;

interface FixtureInterface
{
    public function getDependencies(): array;

    public function load(): void;
}
