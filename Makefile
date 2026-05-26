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
	docker run --rm -v ${PWD}/api:/app -w /app alpine sh -c 'rm -rf .ready var/cache/* var/log/* var/test/*'

api-ready:
	docker run --rm --volume ${PWD}/api:/app --workdir /app alpine touch .ready

api-init: api-permissions api-composer-install api-wait-db api-migrations api-fixtures

api-permissions:
	docker run --rm -v ${PWD}/api:/app -w /app alpine chmod 777 var/cache var/log var/test

api-composer-install:
	docker compose run --rm api-php-cli composer install

api-composer-update:
	docker compose run --rm api-php-cli composer update

api-bash:
	docker compose run --rm api-php-cli bash

api-wait-db:
	docker compose run --rm api-php-cli wait-for-it api-postgres:5432 -t 30

api-migrations:
	docker compose run --rm api-php-cli composer app migrations:migrate -- --no-interaction

api-migrations-diff:
	docker compose run --rm api-php-cli composer app migrations:diff

api-fixtures:
	docker compose run --rm api-php-cli composer app fixtures:load

#api-check: api-validate-schema api-lint api-analyze api-test
api-check: api-lint-check api-test
api-fix: api-lint-fix

api-validate-schema:
	docker compose run --rm api-php-cli composer app orm:validate-schema

api-lint-check: api-php-cs-fixer-check api-rector-check api-php-stan-check
api-lint-fix: api-rector-fix api-php-cs-fixer-fix

api-php-cs-fixer-check:
	docker compose run --rm api-php-cli composer php-cs-fixer:check

api-php-cs-fixer-fix:
	docker compose run --rm api-php-cli composer php-cs-fixer:fix

api-php-stan-check:
	docker compose run --rm api-php-cli composer phpstan:check

api-rector-check:
	docker compose run --rm api-php-cli composer rector:check

api-rector-fix:
	docker compose run --rm api-php-cli composer rector:fix

api-cs-fix:
	docker compose run --rm api-php-cli composer php-cs-fixer fix

api-analyze-diff:
	docker compose run --rm api-php-cli composer psalm

api-clear-cache:
	docker compose run --rm api-php-cli bin/console cache:clear

api-test:
	docker compose run --rm api-php-cli composer test

api-test-coverage:
	docker compose run --rm api-php-cli composer test-coverage

api-test-unit:
	docker compose run --rm api-php-cli composer test -- --testsuite=unit

api-test-unit-coverage:
	docker compose run --rm api-php-cli composer test-coverage -- --testsuite=unit

api-test-functional:
	docker compose run --rm api-php-cli composer test -- --testsuite=functional

api-test-functional-coverage:
	docker compose run --rm api-php-cli composer test-coverage -- --testsuite=functional

#api-migrations:
#	docker compose run --rm api-php-cli composer app migrations:migrate -- --no-interaction
#
#api-migrations-diff:
#	docker compose run --rm api-php-cli composer app migrations:diff
#
#api-fixtures:
#	docker compose run --rm api-php-cli composer app fixtures:load
#
#api-backup:
#	docker compose run --rm api-postgres-backup
#
#api-check: api-validate-schema api-lint api-analyze api-test
#
#api-validate-schema:
#	docker compose run --rm api-php-cli composer app orm:validate-schema
#
#api-lint:
#	docker compose run --rm api-php-cli composer lint
#	docker compose run --rm api-php-cli composer php-cs-fixer fix -- --dry-run --diff
#
#api-cs-fix:
#	docker compose run --rm api-php-cli composer php-cs-fixer fix
#
#api-analyze:
#	docker compose run --rm api-php-cli composer psalm -- --no-diff
#
#api-analyze-diff:
#	docker compose run --rm api-php-cli composer psalm
#
#api-test:
#	docker compose run --rm api-php-cli composer test
#
#api-test-coverage:
#	docker compose run --rm api-php-cli composer test-coverage
#
#api-test-unit:
#	docker compose run --rm api-php-cli composer test -- --testsuite=unit
#
#api-test-unit-coverage:
#	docker compose run --rm api-php-cli composer test-coverage -- --testsuite=unit
#
#api-test-functional:
#	docker compose run --rm api-php-cli composer test -- --testsuite=functional
#
#api-test-functional-coverage:
#	docker compose run --rm api-php-cli composer test-coverage -- --testsuite=functional

#frontend-clear:
#	docker run --rm -v ${PWD}/frontend:/app -w /app alpine sh -c 'rm -rf .ready build'
#
#frontend-init: frontend-yarn-install
#
#frontend-yarn-install:
#	docker compose run --rm frontend-node-cli yarn install
#
#frontend-yarn-upgrade:
#	docker compose run --rm frontend-node-cli yarn upgrade
#
#frontend-yarn-upgrade-local:
#	cd frontend && yarn upgrade
#
#frontend-bash:
#	docker compose run --rm frontend-node-cli
#
#frontend-yarn-outdated:
#	docker compose run --rm frontend-node-cli yarn outdated
#
#frontend-ready:
#	docker run --rm -v ${PWD}/frontend:/app -w /app alpine touch .ready
#
#frontend-check: frontend-lint frontend-test
#
#frontend-lint:
#	docker compose run --rm frontend-node-cli yarn eslint
#	docker compose run --rm frontend-node-cli yarn stylelint
#
#frontend-eslint-fix:
#	docker compose run --rm frontend-node-cli yarn eslint-fix
#
#frontend-pretty:
#	docker compose run --rm frontend-node-cli yarn prettier
#
#frontend-test:
#	docker compose run --rm frontend-node-cli yarn test --watchAll=false
#
#frontend-local-test:
#	cd frontend && yarn test --watchAll=false
#
#frontend-test-watch:
#	docker compose run --rm frontend-node-cli yarn test
#
#cucumber-clear:
#	docker run --rm -v ${PWD}/cucumber:/app -w /app alpine sh -c 'rm -rf var/*'
#
#cucumber-init: cucumber-yarn-install
#
#cucumber-yarn-install:
#	docker compose run --rm cucumber-node-cli yarn install
#
#cucumber-yarn-upgrade:
#	docker compose run --rm cucumber-node-cli yarn upgrade
#
#cucumber-yarn-upgrade-local:
#	cd cucumber && yarn upgrade
#
#cucumber-bash:
#	docker compose run --rm cucumber-node-cli bash
#
#cucumber-yarn-outdated:
#	docker compose run --rm cucumber-node-cli yarn outdated
#
#cucumber-lint:
#	docker compose run --rm cucumber-node-cli yarn lint
#
#cucumber-lint-fix:
#	docker compose run --rm cucumber-node-cli yarn lint-fix
#
#cucumber-smoke:
#	docker compose run --rm cucumber-node-cli yarn smoke
#
#cucumber-e2e:
#	docker compose run cucumber-node-cli yarn e2e
#
#cucumber-report:
#	docker compose run --rm cucumber-node-cli yarn report
#
#build: build-frontend build-api
#
#build-frontend:
#	docker --log-level=debug build --pull --file=frontend/docker/production/nginx/Dockerfile --tag=${REGISTRY}/auction-frontend:${IMAGE_TAG} frontend
#
#build-api:
#	docker --log-level=debug build --pull --file=api/docker/production/nginx/Dockerfile --tag=${REGISTRY}/auction-api:${IMAGE_TAG} api
#	docker --log-level=debug build --pull --file=api/docker/production/php-fpm/Dockerfile --tag=${REGISTRY}/auction-api-php-fpm:${IMAGE_TAG} api
#	docker --log-level=debug build --pull --file=api/docker/production/php-cli/Dockerfile --tag=${REGISTRY}/auction-api-php-cli:${IMAGE_TAG} api
#	docker --log-level=debug build --pull --file=api/docker/common/postgres-backup/Dockerfile --tag=${REGISTRY}/auction-api-postgres-backup:${IMAGE_TAG} api/docker/common
#
#try-build:
#	REGISTRY=localhost IMAGE_TAG=0 make build
#
#push: push-frontend push-api
#
#push-frontend:
#	docker push ${REGISTRY}/auction-frontend:${IMAGE_TAG}
#
#push-api:
#	docker push ${REGISTRY}/auction-api:${IMAGE_TAG}
#	docker push ${REGISTRY}/auction-api-php-fpm:${IMAGE_TAG}
#	docker push ${REGISTRY}/auction-api-php-cli:${IMAGE_TAG}
#	docker push ${REGISTRY}/auction-api-postgres-backup:${IMAGE_TAG}
#
#testing-build: testing-build-testing-api-php-cli testing-build-cucumber
#
#testing-build-testing-api-php-cli:
#	docker --log-level=debug build --pull --file=api/docker/testing/php-cli/Dockerfile --tag=${REGISTRY}/auction-testing-api-php-cli:${IMAGE_TAG} api
#
#testing-build-cucumber:
#	docker --log-level=debug build --pull --file=cucumber/docker/testing/node/Dockerfile --tag=${REGISTRY}/auction-cucumber-node-cli:${IMAGE_TAG} cucumber
#
#testing-init:
#	COMPOSE_PROJECT_NAME=testing docker compose -f docker-compose-testing.yml up -d
#	COMPOSE_PROJECT_NAME=testing docker compose -f docker-compose-testing.yml run --rm api-php-cli wait-for-it api-postgres:5432 -t 60
#	COMPOSE_PROJECT_NAME=testing docker compose -f docker-compose-testing.yml run --rm api-php-cli php bin/app.php migrations:migrate --no-interaction
#	COMPOSE_PROJECT_NAME=testing docker compose -f docker-compose-testing.yml run --rm testing-api-php-cli php bin/app.php fixtures:load --no-interaction
#	sleep 15
#
#testing-smoke:
#	COMPOSE_PROJECT_NAME=testing docker compose -f docker-compose-testing.yml run --rm cucumber-node-cli yarn smoke-ci
#
#testing-e2e:
#	COMPOSE_PROJECT_NAME=testing docker compose -f docker-compose-testing.yml run --rm cucumber-node-cli yarn e2e-ci
#
#testing-down-clear:
#	COMPOSE_PROJECT_NAME=testing docker compose -f docker-compose-testing.yml down -v --remove-orphans
#
#try-testing: try-build try-testing-build try-testing-init try-testing-smoke try-testing-e2e try-testing-down-clear
#
#try-testing-build:
#	REGISTRY=localhost IMAGE_TAG=0 make testing-build
#
#try-testing-init:
#	REGISTRY=localhost IMAGE_TAG=0 make testing-init
#
#try-testing-smoke:
#	REGISTRY=localhost IMAGE_TAG=0 make testing-smoke
#
#try-testing-e2e:
#	REGISTRY=localhost IMAGE_TAG=0 make testing-e2e
#
#try-testing-down-clear:
#	REGISTRY=localhost IMAGE_TAG=0 make testing-down-clear
#
#validate-jenkins:
#	curl --user ${USER} -X POST -F "jenkinsfile=<Jenkinsfile" ${HOST}/pipeline-model-converter/validate
#
#deploy:
#	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'rm -rf site_${BUILD_NUMBER}'
#	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'mkdir site_${BUILD_NUMBER}'
#
## for external traefic
##	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'docker network create --driver=overlay traefik-public || true'
##	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'rm -rf site_${BUILD_NUMBER} && mkdir site_${BUILD_NUMBER}'
#
#	envsubst < docker-compose-production.yml > docker-compose-production-env.yml
#	scp -o StrictHostKeyChecking=no -P ${PORT} docker-compose-production-env.yml deploy@${HOST}:site_${BUILD_NUMBER}/docker-compose.yml
#	rm -f docker-compose-production-env.yml
#
#	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'mkdir site_${BUILD_NUMBER}/secrets'
#	scp -o StrictHostKeyChecking=no -P ${PORT} ${API_DB_PASSWORD_FILE} deploy@${HOST}:site_${BUILD_NUMBER}/secrets/api_db_password
#	scp -o StrictHostKeyChecking=no -P ${PORT} ${API_MAILER_PASSWORD_FILE} deploy@${HOST}:site_${BUILD_NUMBER}/secrets/api_mailer_password
#	scp -o StrictHostKeyChecking=no -P ${PORT} ${SENTRY_DSN_FILE} deploy@${HOST}:site_${BUILD_NUMBER}/secrets/sentry_dsn
#	scp -o StrictHostKeyChecking=no -P ${PORT} ${JWT_ENCRYPTION_KEY_FILE} deploy@${HOST}:site_${BUILD_NUMBER}/secrets/jwt_encryption_key
#	scp -o StrictHostKeyChecking=no -P ${PORT} ${JWT_PUBLIC_KEY} deploy@${HOST}:site_${BUILD_NUMBER}/secrets/jwt_public_key
#	scp -o StrictHostKeyChecking=no -P ${PORT} ${JWT_PRIVATE_KEY} deploy@${HOST}:site_${BUILD_NUMBER}/secrets/jwt_private_key
#	scp -o StrictHostKeyChecking=no -P ${PORT} ${BACKUP_AWS_SECRET_ACCESS_KEY_FILE} deploy@${HOST}:site_${BUILD_NUMBER}/secrets/backup_aws_secret_access_key
#
#	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && docker stack deploy --compose-file docker-compose.yml auction --with-registry-auth --prune'
#
#deploy-clean:
#	rm -f docker-compose-production-env.yml
#
#rollback:
#ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && docker stack deploy --compose-file docker-compose.yml auction --with-registry-auth --prune'
