detected_OS := $(shell uname 2>/dev/null || echo Unknown)
ifeq ($(detected_OS), Linux)
	alpine_count := $(shell cat /etc/os-release | grep alpine -c)
	ifeq ($(alpine_count), 0)
		env_prefix := docker-compose exec php
	endif
endif

docker-up: docker-down
	@touch .docker/.zsh_history
	docker-compose up -d --build

docker-down:
	docker-compose down

docker-restart: docker-up

php:
	docker-compose exec php zsh

build: composer warmup migrate

build-prod: composer-prod warmup migrate

composer:
	$(env_prefix) composer install --no-interaction

composer-prod:
	$(env_prefix) composer install --no-dev --optimize-autoloader --no-interaction

migrate:
	$(env_prefix) bin/console doctrine:migrations:migrate --no-interaction

warmup:
	$(env_prefix) bin/console cache:clear
	$(env_prefix) bin/console cache:warmup

lint:
	$(env_prefix) composer run-script lint

static-analysis:
	$(env_prefix) composer run-script static-analysis

deptrac:
	$(env_prefix) composer run-script deptrac
