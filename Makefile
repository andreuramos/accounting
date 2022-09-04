#!/bin/bash
BE_NAME := accounting_php

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

test: # runs all tests
	docker exec -it $(BE_NAME)_1 vendor/bin/phpunit tests/ --colors=always
