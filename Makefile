.DEFAULT_GOAL := help

.PHONY: help
help: ## Displays this help
	@grep -E '(^[a-zA-Z_-]+:.*?## .*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[36m##/[33m/'


DOCKER_COMPOSE  ?= docker-compose

SYMFONY          = symfony
CONSOLE          = $(SYMFONY) console
COMPOSER         = $(SYMFONY) composer
PHP				 = $(SYMFONY) php

##
## Setup
## -----
.PHONY: init-config  install down start stop

init-config: ## Initialize project configuration files
	cp -n .env .env.local

install: ## Install project
	make start
	make composer-install
	make create-db

down: ## Kills the docker containers
	$(DOCKER_COMPOSE) down --volumes --remove-orphans

start: ## Starts the docker containers
	$(DOCKER_COMPOSE) up -d --remove-orphans

stop: ## Stops the docker containers
	$(DOCKER_COMPOSE) stop

##
## Database
## --------
.PHONY: create-db

create-db: ## Create database
	$(CONSOLE) doctrine:database:drop --if-exists --force
	$(CONSOLE) doctrine:database:create
	$(CONSOLE) doctrine:migrations:migrate --no-interaction
	$(CONSOLE) doctrine:fixtures:load --no-interaction

##
## Tools
## -----
.PHONY: apply-php-cs-fixer
apply-php-cs-fixer: vendor/bin/php-cs-fixer ## apply php-cs-fixer fixes
	$(PHP) vendor/bin/php-cs-fixer fix --using-cache no --verbose --diff

composer-install: ## Installs php dependencies
	$(COMPOSER) install

phpstan: vendor/bin/phpstan ## phpstan
	$(PHP) vendor/bin/phpstan analyse -c phpstan.neon.dist
