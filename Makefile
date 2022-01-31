# Executables (local)
DOCKER_COMP = docker-compose
# Docker containers
PHP_CONT = $(DOCKER_COMP) exec php
# Executables
PHP      = $(PHP_CONT) php
COMPOSER = $(PHP_CONT) composer
SYMFONY  = $(PHP_CONT) bin/console

.PHONY: it
it: coding-standards dependency-analysis static-code-analysis

.build/docker/build: api/docker api/Dockerfile pwa/Dockerfile ## Builds the Docker images
	@$(DOCKER_COMP) build --pull
	mkdir --parents .build/docker/
	touch .build/docker/build

.build/docker/up: .build/docker/build docker-compose.yaml docker-compose.override.yaml
	@$(DOCKER_COMP) up --detach
	mkdir --parents .build/docker/
	touch .build/docker/up

.PHONY: coding-standards
coding-standards: start ## Normalizes composer.json with ergebnis/composer-normalize, lints YAML files with yamllint and fixes code style issues with squizlabs/php_codesniffer
	@$(COMPOSER) normalize
	@$(PHP_CONT) vendor/bin/config-transformer switch-format config/ --target-symfony-version 5.3
	@$(PHP_CONT) mkdir -p .build/php_codesniffer
	@$(PHP_CONT) vendor/bin/phpcbf
	@$(PHP_CONT) vendor/bin/phpcs

.PHONY: dependency-analysis
dependency-analysis: start ## Runs a dependency analysis with maglnet/composer-require-checker
	@$(PHP_CONT) vendor/bin/composer-require-checker check --config-file=composer-require-checker.json

.PHONY: down
down: ## Stop and remove the docker hub
	@$(DOCKER_COMP) down --remove-orphans
	rm -f .build/docker/up

.PHONY: help
help: ## Displays this list of targets with descriptions
	@grep -E '^[a-zA-Z0-9_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: logs
logs: start ## Show live logs
	@$(DOCKER_COMP) logs --tail=0 --follow

.PHONY: static-code-analysis
static-code-analysis: start ## Runs a static code analysis with phpstan/phpstan and vimeo/psalm
	@$(PHP_CONT) mkdir -p .build/phpstan
	@$(PHP_CONT) vendor/bin/phpstan analyse --configuration=phpstan.neon.dist --memory-limit=-1
	@$(PHP_CONT) mkdir -p .build/psalm
	@$(PHP_CONT) vendor/bin/psalm --config=psalm.xml --diff --show-info=false --stats --threads=4

.PHONY: static-code-analysis-baseline
static-code-analysis-baseline: start ## Generates a baseline for static code analysis with phpstan/phpstan and vimeo/psalm
	@$(PHP_CONT) mkdir -p .build/phpstan
	@$(PHP_CONT) vendor/bin/phpstan analyze --configuration=phpstan.neon.dist --generate-baseline=phpstan-baseline.neon --memory-limit=-1 > phpstan-baseline.neon
	@$(PHP_CONT) mkdir -p .build/psalm
	@$(PHP_CONT) vendor/bin/psalm --config=psalm.xml --set-baseline=psalm-baseline.xml

.PHONY: start
start: .build/docker/up ## Build and start the containers

.PHONY: stop
stop: ## Stop the docker hub
	@$(DOCKER_COMP) stop
	rm -f .build/docker/up
