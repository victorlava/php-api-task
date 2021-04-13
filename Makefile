.PHONY: start stop init build tests

start:
	docker-compose up -d

stop:
	docker-compose stop

init:
	docker-compose build
	docker-compose up -d
	docker-compose exec php composer install
	docker-compose exec php php bin/console doctrine:database:create
	docker-compose exec php php bin/console doctrine:database:create --env=test
	docker-compose exec php php bin/console doctrine:migrations:migrate --no-interaction
	docker-compose exec php php bin/console doctrine:fixtures:load --no-interaction

build:
	build/build.sh

fixtures:
	docker-compose exec php php bin/console doctrine:fixtures:load --no-interaction

cache:
	docker-compose exec php php bin/console cache:clear --no-interaction
	docker-compose exec php composer clear-cache

tests:
	docker-compose exec php php bin/console doctrine:migrations:migrate --no-interaction --env=test
	docker-compose exec php php vendor/bin/simple-phpunit --verbose --testdox  --log-junit=report.xml

unit:
	docker-compose exec php php vendor/bin/simple-phpunit --verbose --testdox --testsuite=unit
