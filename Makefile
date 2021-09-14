.DEFAULT_GOAL := help

.PHONY: help
help: ## display command overview
	@grep -E '^[a-zA-Z0-9_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[35m%-30s\033[0m %s\n", $$1, $$2}'

install: ## install dependencies
	composer update --no-interaction --no-progress --no-ansi
	phive install

clean: ## cleanup installed dependencies and lock files
	rm -rf composer.lock
	rm -rf vendor

.PHONY: cs
cs: ## enforce code style
	vendor/bin/ecs check --fix
	composer normalize

.PHONY: analysis
analysis: ## run static code analysis
	vendor/bin/phpstan
	vendor/bin/psalm

.PHONY: check
check: | cs analysis test ## run all quality checks
	composer validate

.PHONY: test
test: ## run unit tests
	vendor/bin/phpunit
	XDEBUG_MODE=coverage tools/infection
