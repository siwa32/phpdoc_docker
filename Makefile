## ---------------------------------------
## Docker composer management
## ---------------------------------------
up:
	docker compose --file ./compose.yml up -d
down:
	docker compose --file ./compose.yml down
reup:
	docker compose --file ./compose.yml down
	docker compose --file ./compose.yml up -d
start:
	docker compose --file ./compose.yml start
stop:
	docker compose --file ./compose.yml stop
build:
	docker compose --file ./compose.yml build
destroy:
	docker compose --file ./compose.yml down --rmi all --volumes

## ---------------------------------------
## Docker container console
## ---------------------------------------
php:
	docker compose --file ./compose.yml exec php sh

## ---------------------------------------
## Docker composer informational
## ---------------------------------------
ps:
	docker compose --file ./compose.yml ps
