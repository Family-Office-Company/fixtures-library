# Contributing

## Code of Conduct

Before contributing to this project, please read our [code of conduct](.github/CODE_OF_CONDUCT.md).

## CI/CD Pipelines

We are using [GitHub Actions](https://github.com/features/actions) as a continuous integration system.

For details, take a look at the following workflow configuration files:

- [`workflows/lint.yaml`](workflows/lint.yaml)
- [`workflows/static-code-analysis.yaml`](workflows/static-code-analysis.yaml)
- [`workflows/test.yaml`](workflows/test.yaml)

## Coding Standards

We are using [`ergebnis/composer-normalize`](https://github.com/ergebnis/composer-normalize) to
normalize `composer.json`.

We are using [`yamllint`](https://github.com/adrienverge/yamllint) to enforce coding standards in YAML files.

We are using [`sclable/xml-lint`](https://github.com/sclable/xml-lint) to enforce coding standards in XML files.

We are using [`symplify/easy-coding-standard`](https://github.com/symplify/easy-coding-standard)
and [`rector/rector`](rector/rector) to enforce coding standards in PHP files
and Markdown code snippets.

Run

```sh
make cs
```

to automatically fix coding standard violations.

## Static Code Analysis

We are using [`maglnet/composer-require-checker`](https://github.com/maglnet/ComposerRequireChecker) to prevent the use
of unknown symbols in production code.

We are using [`vimeo/psalm`](https://github.com/vimeo/psalm) and [`phpstan/phpstan`](https://github.com/phpstan/phpstan) to
statically analyze the code.

Run

```sh
make analysis
```

to run a static code analysis.

## Tests

We are using [`phpunit/phpunit`](https://github.com/sebastianbergmann/phpunit) to drive the development.

We are using [`infection/infection`](https://github.com/infection/infection)
and [`roave/no-leaks`](https://github.com/roave/no-leaks) to ensure a minimum quality of the tests.

Run

```sh
$ make tests
```

to run all the tests.

## Extra lazy?

Run

```sh
make check
```

to enforce coding standards, run a static code analysis, and run tests!

## Help

:bulb: Run

```sh
make help
```

to display a list of available targets with corresponding descriptions.
