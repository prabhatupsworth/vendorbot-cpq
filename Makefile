# Vendorbot Makefile (Production Ready - Docker)

SHELL := /bin/bash

APP_CONTAINER=vendorbot-cpq

# -----------------------------
# Docker
# -----------------------------

up:
	docker-compose up -d --build

down:
	docker-compose down

restart:
	docker-compose down && docker-compose up -d --build

logs:
	docker-compose logs -f

ps:
	docker ps

# -----------------------------
# App (Container Commands)
# -----------------------------

bash:
	docker exec -it $(APP_CONTAINER) bash

install:
	docker exec -it $(APP_CONTAINER) composer install

key:
	docker exec -it $(APP_CONTAINER) php artisan key:generate

migrate:
	docker exec -it $(APP_CONTAINER) php artisan migrate

fresh:
	docker exec -it $(APP_CONTAINER) php artisan migrate:fresh --seed

seed:
	docker exec -it $(APP_CONTAINER) php artisan db:seed

queue:
	docker exec -it $(APP_CONTAINER) php artisan queue:work

clear:
	docker exec -it $(APP_CONTAINER) php artisan optimize:clear

# -----------------------------
# Setup & Run
# -----------------------------

setup:
	make up
	make install
	make key
	make migrate

start:
	make up

stop:
	make down

reset:
	make down
	make up
	make fresh
