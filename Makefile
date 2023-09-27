#!/bin/bash
FE_NAME := accounting_node
DB_CONTAINER := accounting_mysql
include api/.env

help: # lists commands
	printf "hoola"

up: # inits dev environment
	docker compose up -d --build

down: # unmount dev environment
	docker compose down

init-be: # inits php app
	docker compose exec api composer install

enter-be: # execs shell inside php container
	docker compose exec api /bin/bash

init-fe:
	docker exec -it $(FE_NAME)_1

enter-db:
	docker compose exec mysql mysql -u$(DB_USER) -p$(DB_PWD) $(DB_NAME)

test: test-static test-unit test-integration

test-integration: # runs all tests
	mkdir -p api/tmp
	docker compose exec -u $(shell id -u) api vendor/bin/phinx migrate --configuration=config/phinx.php -e testing
	docker compose exec api vendor/bin/phpunit --colors=always --testsuite integration

test-unit:
	docker compose exec api vendor/bin/phpunit --colors=always --testsuite unit

test-static:
	docker compose exec api vendor/bin/phpcs src/ --standard=PSR12

migration: create-migration

create-migration:
	docker compose exec -u $(shell id -u) api vendor/bin/phinx create $(name) --configuration=config/phinx.php

migrate:
	docker compose exec -u $(shell id -u) api vendor/bin/phinx migrate --configuration=config/phinx.php -e development

run-command:
	docker compose exec -u $(shell id -u) api php config/console.php $(command)
