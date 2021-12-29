# Advanced Usage

Please check the official [README](README.md) for installation and basic usage instructions.

## Creating a Custom Fixture Factory

In some cases, you might want to create a custom fixture factory to be able to fetch the fixture from a dependency
injection container or otherwise pre-configure or build the instance to your needs.

Creating such a custom fixture factory is as easy as it gets.

Create a class implementing the [`FixtureFactoryInterface`](src/FixtureFactoryInterface.php), which is set to provide
you with a blueprint of the factory.

The rest is left to you. Fetch the fixture from a dependency injection container, pre-configure it or simply add
additional logging like in the example below.

```php
namespace FamilyOffice\FixturesLibrary\Example\Advanced\Factory;

use FamilyOffice\FixturesLibrary\Factory\DefaultFixtureFactory;
use FamilyOffice\FixturesLibrary\FixtureFactoryInterface;
use FamilyOffice\FixturesLibrary\FixtureInterface;

final class CustomFixtureFactory implements FixtureFactoryInterface
{
    private DefaultFixtureFactory $defaultFixtureFactory;

    public function __construct()
    {
        $this->defaultFixtureFactory = new DefaultFixtureFactory();
    }

    public function createInstance(string $fixtureClass): FixtureInterface
    {
        echo sprintf('creating instance of %s%s', $fixtureClass, PHP_EOL);

        return $this->defaultFixtureFactory->createInstance($fixtureClass);
    }
}
```

## Creating a Custom Fixture Computer

While building the dependency chain, fixtures are being marked as computed if their dependencies were loaded to ensure
no fixture is being computed twice.

During the process of marking fixtures as computed, we get an opportunity to hook into the process using a custom
fixture computer.

A production ready example for a custom computer is
the [`OnFlyFixtureComputer`](src/Computer/OnFlyFixtureComputer.php). It hooks into the computing process and executes
all fixtures on-the-fly.

The example implementation adds additional logging to the computing process.

We also provide a blueprint interface for custom fixture computers aswell.

```php
namespace FamilyOffice\FixturesLibrary\Example\Advanced\Computer;

use FamilyOffice\FixturesLibrary\FixtureComputerInterface;
use FamilyOffice\FixturesLibrary\FixtureInterface;

final class CustomComputer implements FixtureComputerInterface
{
    public function computeFixture(FixtureInterface $fixture): void
    {
        echo sprintf('computing %s%s', \get_class($fixture), PHP_EOL);
    }
}
```

### Application

The custom fixture factory and custom computer can be passed to the `ChainBuilder`'s constructor and eventually to
the `ChainLoader`'s constructor.

```php
$customFixtureFactory = new CustomFixtureFactory();
$customComputer = new CustomComputer();
$chainBuilder = new ChainBuilder($customFixtureFactory, $customComputer);
```

## Creating a Custom Fixture Loader

If the dependency chain is first built and then loaded, we haven't gotten a chance yet to hook into the loading process.

This can be done by creating a custom fixture loader.

The blueprint interface for custom fixture loaders is [`FixtureLoaderInterface`](src/FixtureLoaderInterface.php)

In this example, we again add additional logging to the loading process.

```php
namespace FamilyOffice\FixturesLibrary\Example\Advanced\Loader;

use FamilyOffice\FixturesLibrary\FixtureInterface;
use FamilyOffice\FixturesLibrary\FixtureLoaderInterface;
use FamilyOffice\FixturesLibrary\Loader\DefaultFixtureLoader;

final class CustomFixtureLoader implements FixtureLoaderInterface
{
    private DefaultFixtureLoader $defaultFixtureLoader;

    public function __construct()
    {
        $this->defaultFixtureLoader = new DefaultFixtureLoader();
    }

    public function loadFixture(FixtureInterface $fixture): void
    {
        echo sprintf('loading %s%s', \get_class($fixture), PHP_EOL);

        $this->defaultFixtureLoader->loadFixture($fixture);
    }
}
```

### Application

The custom fixture loader instance can then be passed to the `ChainLoader`'s constructor.

```php
$customFixtureLoader = new CustomFixtureLoader();
$dependencyChainLoader = new ChainLoader($customFixtureFactory, $customFixtureLoader);
```

## Full Example

A full example of this can be found [here](example/Advanced).
