#!/bin/bash
DB_CONTAINER := accounting_mysql
CURRENT_USER := $(shell id -u):$(shell id -g)
include api/.env

help: # lists commands
	printf "hoola"

up: # inits dev environment
	docker-compose up -d --build

down: # unmount dev environment
	docker-compose down

init-be: # inits php app
	docker-compose run --no-deps api composer install

enter-be: # execs shell inside php container
	docker-compose exec api /bin/bash

enter-db:
	docker-compose exec mysql mysql -u$(DB_USER) -p$(DB_PWD) $(DB_NAME)

test: test-static test-unit test-integration

test-integration: # runs all tests
	mkdir -p api/tmp
	docker-compose up -d
	sleep 5
	docker-compose run api vendor/bin/phinx migrate --configuration=config/phinx.php -e testing
	docker-compose run api vendor/bin/phpunit --colors=always --testsuite integration

test-unit:
	docker-compose run api vendor/bin/phpunit --colors=always --testsuite unit

test-static:
	docker-compose run api vendor/bin/phpcs src/ --standard=PSR12

migration: create-migration

create-migration:
	docker-compose run -u $(CURRENT_USER) api vendor/bin/phinx create $(name) --configuration=config/phinx.php

migrate:
	docker-compose run -u $(CURRENT_USER) api vendor/bin/phinx migrate --configuration=config/phinx.php -e development

run-command:
	docker-compose run -u $(CURRENT_USER) api php config/console.php $(command)
