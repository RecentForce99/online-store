init: # Сделать полную инициализацию приложения
	composer install --ansi --prefer-dist

#test: # Выполнить тесты приложения
#	@echo test

up: # Создать и запустить контейнеры
	sudo docker compose --env-file .env.local up -d

down: # остановить контейнеры
	sudo docker compose --env-file .env.local stop

restart: down up # Рестарт всех контейнеров
