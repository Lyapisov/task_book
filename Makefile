.DEFAULT_GOAL := help
help:
	@grep -E '^[a-zA-Z-]+:.*?## .*$$' Makefile | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "[32m%-17s[0m %s\n", $$1, $$2}'
.PHONY: help

connect-php: ## Connect php
	docker-compose exec backend-php-cli bash

connect-db: ## Connect db
	docker-compose exec db bash

initialize-locally: ## Initialize project
	docker-compose pull
	docker-compose up --build --remove-orphans -d
	docker-compose run --rm backend-php-cli composer install
	docker-compose run --rm backend-php-cli vendor/bin/phinx migrate
