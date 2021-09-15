.DEFAULT_GOAL := help

.PHONY: help
help: ## display command overview
	@grep -E '^[a-zA-Z0-9_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[35m%-30s\033[0m %s\n", $$1, $$2}'

install: ## install dependencies
	composer update --no-interaction --no-progress --no-ansi
	phive --no-progress install --trust-gpg-keys C5095986493B4AA0

clean: ## cleanup installed dependencies and lock files
	rm -rf logs
	rm -rf tools
	rm -rf vendor
	rm -rf composer.lock

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
	XDEBUG_MODE=coverage tools/infection --min-msi=100 --min-covered-msi=100

.PHONY: start
start: ## start docker environment
	docker-compose up -d

.PHONY: stop
stop: ## stop docker environment
	docker-compose down

.PHONY: restart
restart: stop start ## restart docker environment

.PHONY: ssh
ssh: ## ssh into docker environment
	docker exec -it fixtures_library_devcontainer /bin/bash
