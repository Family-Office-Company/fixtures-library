<h1 align="center">
    <img src=".github/project-logo.svg" width="512px">
</h1>

[![Infection MSI](https://badge.stryker-mutator.io/github.com/Family-Office-Company/fixture-library/main)](https://infection.github.io)

# Fixtures Library

An easy-to-use library for fixture and dependency loading.

## Usage

### Installation

```shell
composer require family-office/fixtures-library
```

### Creating a fixture

Fixtures are regular classes implementing the `FixtureInterface`.

```php
<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary\Example;

use FamilyOffice\FixturesLibrary\FixtureInterface;

class ExampleParentFixture implements FixtureInterface
{
    public function getDependencies(): array
    {
        return [];
    }

    public function load(): void
    {
        // TODO: Implement load() method.
    }
}
```

All code that should be executed within the fixture should live in the `load` method.

Sometimes, fixtures need to depend on each other because they must be executed in a certain order.

All dependencies a fixture is dependent on should be returned from the `getDependencies` method.

```php
<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary\Example;

use FamilyOffice\FixturesLibrary\FixtureInterface;

class ExampleParentFixture implements FixtureInterface
{
    public function getDependencies(): array
    {
        return [ExampleChildFixture::class];
    }

    public function load(): void
    {
        // TODO: Implement load() method.
    }
}
```

### Building the dependency chain

#### Fixture Factory

The fixture factory is essential for the procedure because it tells the fixture loader how to create instances of
fixture classes.

In this example, we're simply creating a new object from the `class-string`, but your use-case might involve fetching it
from a DI container or similar.

All fixture factories must implement the `FixtureFactoryInterface`.

```php
<?php

declare(strict_types=1);

namespace FamilyOffice\FixturesLibrary\Example;

use FamilyOffice\FixturesLibrary\FixtureFactoryInterface;
use FamilyOffice\FixturesLibrary\FixtureInterface;

class MyFixtureFactory implements FixtureFactoryInterface
{
    public function createInstance(string $fixtureClass): FixtureInterface
    {
        return new $fixtureClass();
    }
}
```

We can now create a fixture factory object to pass it to the chain builders' and fixture loaders' constructor later on.

```php
$fixtureFactory = new MyFixtureFactory();
```

#### Chain Builder

To be able to load all fixtures and their dependencies, the fixture loader needs a dependency chain to work with.

Creating a dependency chain is fairly simple:

```php
<?php

declare(strict_types=1);

use FamilyOffice\FixturesLibrary\Dependency\ChainBuilder;
use FamilyOffice\FixturesLibrary\Example\ExampleParentFixture;

require __DIR__ . '/../vendor/autoload.php';

$fixtureFactory = new MyFixtureFactory();
$chainBuilder = new ChainBuilder($fixtureFactory);

$fixtures = [new ExampleParentFixture()/* ... */];
$dependencyChain = $chainBuilder->build($fixtures);
```

### Loading the dependency chain

The only thing left to do is loading the dependency chain using the fixture loader:

```php
// (...)

$dependencyChain = $chainBuilder->build($fixtures);

$fixtureLoader = new Loader($fixtureFactory);
$fixtureLoader->loadDependencyChain($dependencyChain);
```

## License

This project is licensed under the [MIT](LICENSE) license.  
Feel free to do whatever you want with the code!
