# build and run
.PHONY: app-run
app-run:
	docker compose up -d

# down
.PHONY: app-down
app-down:
	docker compose down --remove-orphans

# create the database
.PHONY: database-create
database-create:
	docker compose run --rm backend-php-cli bin/console doctrine:database:create

# run migrations
.PHONY: migrations-run
migrations-run:
	docker compose run --rm backend-php-cli bin/console doctrine:migrations:migrate

# run composer install
.PHONY: composer-install
composer-install:
	docker compose run --rm backend-php-cli composer install