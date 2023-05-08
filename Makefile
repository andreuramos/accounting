#!/bin/bash
BE_NAME := accounting_php
FE_NAME := accounting_node
DB_CONTAINER := accounting_mysql
include api/.env

help: # lists commands
	printf "hoola"

up: # inits dev environment
	docker-compose up -d --build

down: # unmount dev environment
	docker-compose down

init-be: # inits php app
	docker exec -it $(BE_NAME)_1 composer install

enter-be: # execs shell inside php container
	docker exec -it $(BE_NAME)_1 /bin/bash

init-fe:
	docker exec -it $(FE_NAME)_1

enter-db:
	docker exec -it $(DB_CONTAINER)_1 mysql -uaccounting -p

init-db:
	@echo "Initing database ..."
	export DB_USER=$(DB_USER) && \
	export DB_PWD=$(DB_PWD) && \
	export DB_NAME=$(DB_NAME) && \
	envsubst < ./api/config/db-init.sql | docker exec -i $(DB_CONTAINER)_1 mysql -uroot -p$(DB_ROOT_PASSWORD)

test: test-unit test-integration

test-integration: # runs all tests
	docker exec -u 1000 $(BE_NAME)_1 vendor/bin/phinx migrate --configuration=config/phinx.php -e testing
	docker exec $(BE_NAME)_1 vendor/bin/phpunit --colors=always --testsuite integration

test-unit:
	docker exec $(BE_NAME)_1 vendor/bin/phpunit --colors=always --testsuite unit

create-migration:
	docker exec -u 1000 -it $(BE_NAME)_1 vendor/bin/phinx create $(name) --configuration=config/phinx.php

migrate:
	docker exec -u 1000 -it $(BE_NAME)_1 vendor/bin/phinx migrate --configuration=config/phinx.php -e development
