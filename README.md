<h1 align="center">
    <img src=".github/project-logo.svg" width="512px">
</h1>

# Fixtures Library

An easy-to-use library for fixture and dependency loading.

## Installation

```shell
composer require family-office/fixtures-library
```

## Usage

### Creating a fixture

Fixtures are regular classes implementing the [`FixtureInterface`](src/FixtureInterface.php).

```php
namespace FamilyOffice\FixturesLibrary\Example\Basic\Fixtures;

use FamilyOffice\FixturesLibrary\FixtureInterface;

final class EarFixture implements FixtureInterface
{
    public function getDependencies(): array
    {
        return [];
    }

    public function load(): void
    {
        // todo: implement data loading
    }
}
```

All code that should be executed within the fixture should live in the `load` method.

Sometimes, fixtures need to depend on each other because they must be executed in a certain order.

All dependencies a fixture is dependent on should be returned from the `getDependencies` method.

```php
namespace FamilyOffice\FixturesLibrary\Example\Basic\Fixtures;

use FamilyOffice\FixturesLibrary\FixtureInterface;

final class ElephantFixture implements FixtureInterface
{
    public function getDependencies(): array
    {
        return [EarFixture::class];
    }

    public function load(): void
    {
        // todo: implement data loading
    }
}
```

### Loading the Fixtures

The quickest and easiest way to load the fixtures is by creating a default chain builder instance.

```php
$defaultChainBuilder = ChainBuilder::createQuickLoader();
```

The fixtures can then be easily loaded on-the-fly as the dependency tree is built.

```php
$defaultChainBuilder->build([new ElephantFixture()]);
```

### Full Example

A full example of this can be found [here](./example/Basic).

### Advanced Usage

The dependency chain building and loading process can be fully customized to your needs.

An extended documentation on the advanced capabilities of this library can be found [here](docs/advanced.md).

## License

This project is licensed under the [MIT](LICENSE) license.  
Feel free to do whatever you want with the code!
