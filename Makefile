init: # Сделать полную инициализацию приложения
	make dc_build;
	make dc_up;
	php bin/console doctrine:migrations:migrate;
	php bin/console doctrine:fixtures:load --append;

###> Composer ###
c_tests:
	docker exec online-store_php-fpm ./vendor/bin/phpunit tests
###< Composer ###

#test: # Выполнить тесты приложения
#	@echo test

###> Docker compose v2 (screw v1) ###
dc_ps:
	docker compose -f ./docker/docker-compose.yml ps
dc_logs:
	docker compose -f ./docker/docker-compose.yml logs -f
dc_link_env:
	ln -s ./../.env ./docker/.env

dc_reload:
	make dc_down
	make dc_up

dc_restart:
	make dc_down
	make dc_build
	make dc_up

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
###< Docker compose ###
