init: dc_build dc_up # Сделать полную инициализацию приложения
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
dc_ps:
	docker compose -f ./docker/docker-compose.yml ps
dc_logs:
	docker compose -f ./docker/docker-compose.yml logs -f
dc_link_env:
	ln -s ./../.env ./docker/.env

dc_reload: dc_down dc_up

dc_restart: dc_down dc_build dc_up

dc_build:
	docker compose -f ./docker/docker-compose.yml build

dc_start:
	docker compose -f ./docker/docker-compose.yml start
dc_stop:
	docker compose -f ./docker/docker-compose.yml stop

dc_up:
	docker compose -f ./docker/docker-compose.yml up -d --remove-orphans
dc_down:
	docker compose -f ./docker/docker-compose.yml down --remove-orphans

dc_drop:
	@echo "WARNING: This command will remove all containers, volumes, and images! Proceed? (y/n)"
	@read answer && [ $$answer = y ] && docker compose -f ./docker/docker-compose.yml down -v --rmi=all --remove-orphans || echo "Aborted."
###</ Docker compose ###
