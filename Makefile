#!/bin/bash
BE_NAME := accounting_php
FE_NAME := accounting_node

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

test: # runs all tests
	docker exec -it $(BE_NAME)_1 vendor/bin/phpunit tests/ --colors=always

create-migration:
	docker exec -u 1000 -it $(BE_NAME)_1 vendor/bin/phinx create $(name) --configuration=config/phinx.php

migrate:
	docker exec -u 1000 -it $(BE_NAME)_1 vendor/bin/phinx migrate --configuration=config/phinx.php -e development
