init: init-ci
init-ci: docker-down-clear \
	api-clear \
	docker-pull docker-build docker-up \
	api-init \
	api-ready
up: docker-up
down: docker-down
restart: down up
rebuild: down init
test: api-test
bash: api-bash
clear-cache: api-clear-cache
check: api-check
fix: api-fix

docker-up:
	docker compose up -d

docker-down:
	docker compose down --remove-orphans

docker-down-clear:
	docker compose down -v --remove-orphans

docker-pull:
	docker compose pull

docker-build:
	docker compose build --pull

docker-build-no-cache:
	docker compose build --pull --no-cache

api-clear:
	docker run --rm -v ${PWD}/api:/app -w /app alpine sh -c 'rm -rf .ready bootstrap/cache/*.php'

api-ready:
	docker run --rm --volume ${PWD}/api:/app --workdir /app alpine touch .ready

api-init: api-permissions api-composer-install api-env api-key api-wait-db api-migrations

api-permissions:
	docker run --rm -v ${PWD}/api:/app -w /app alpine chmod -R 777 storage bootstrap/cache

api-composer-install:
	docker compose run --rm api composer install

api-composer-update:
	docker compose run --rm api composer update

api-env:
	docker run --rm -v ${PWD}/api:/app -w /app alpine sh -c 'test -f .env || cp .env.example .env'

api-key:
	docker compose run --rm api php artisan key:generate

api-bash:
	docker compose run --rm api bash

api-wait-db:
	docker compose run --rm api wait-for-it api-postgres:5432 -t 30

api-migrations:
	docker compose run --rm api php artisan migrate --force

api-seed:
	docker compose run --rm api php artisan db:seed --force

api-check: api-lint-check api-test
api-fix: api-lint-fix

api-lint-check:
	docker compose run --rm api vendor/bin/pint --test

api-lint-fix:
	docker compose run --rm api vendor/bin/pint

api-clear-cache:
	docker compose run --rm api php artisan optimize:clear

api-test:
	docker compose run --rm api composer test

api-test-coverage:
	docker compose run --rm api php artisan test --coverage
