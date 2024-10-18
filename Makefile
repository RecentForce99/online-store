init: # Сделать полную инициализацию приложения
	composer install --ansi --prefer-dist
	npm i

#test: # Выполнить тесты приложения
#	@echo test

up: # Создать и запустить контейнеры
	sudo docker-compose up -d

down: # остановить контейнеры
	sudo docker-compose stop

restart: down up # Рестарт всех контейнеров
