init: dc.build dc.up # Сделать полную инициализацию приложения
	php bin/console doctrine:migrations:migrate;
	php bin/console doctrine:database:create --env=test
	php bin/console lexik:jwt:generate-keypair

check: fix\:code-style test

###< Composer ###
test:
	docker exec online-store_php-fpm composer test
fix\:code-style:
	docker exec online-store_php-fpm composer fix:code-style
###</ Composer ###

###< Docker compose v2 (screw v1) ###
dc.ps:
	docker compose -f ./docker/docker-compose.yml ps
dc.logs:
	docker compose -f ./docker/docker-compose.yml logs -f
dc.link_env:
	ln -s ./../.env ./docker/.env

dc.reload: dc.down dc.up

dc.restart: dc.down dc.build dc.up

dc.build:
	docker compose -f ./docker/docker-compose.yml build

dc.start:
	docker compose -f ./docker/docker-compose.yml start
dc.stop:
	docker compose -f ./docker/docker-compose.yml stop

dc.up:
	docker compose -f ./docker/docker-compose.yml up -d --remove-orphans
dc.down:
	docker compose -f ./docker/docker-compose.yml down --remove-orphans

dc.drop:
	@echo "WARNING: This command will remove all containers, volumes, and images! Proceed? (y/n)"
	@read answer && [ $$answer = y ] && docker compose -f ./docker/docker-compose.yml down -v --rmi=all --remove-orphans || echo "Aborted."
###</ Docker compose ###
