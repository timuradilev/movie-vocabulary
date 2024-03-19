# build frontend
.PHONE: frontend-build
frontend-build:
	docker build -t frontend -f frontend/docker/development/Dockerfile ${PWD}/frontend/app

# run frontend
.PHONY: frontend-run
frontend-run:
	docker run --rm -d --name frontend -v ${PWD}/frontend/app:/app -p 5173:5173 frontend sh -c "npm i ; npm run dev -- --host"

# stop frontend
.PHONY: frontend-stop
frontend-stop:
	docker stop frontend

# install npm packages
.PHONY: frontend-npm-install
frontend-npm-install:
	docker run --rm -v ${PWD}/frontend/app:/app frontend npm i

# build gateway
.PHONE: gateway-build
gateway-build:
	docker build -t gateway -f gateway/docker/development/Dockerfile ${PWD}/gateway

# run gateway
.PHONY: gateway-run
gateway-run:
	docker run --rm -d --name gateway -p 8080:8080 -p 8081:8081 --network mynetwork gateway

# stop gateway
.PHONY: gateway-stop
gateway-stop:
	docker stop gateway

.PHONY: backend-nginx-build
backend-nginx-build:
	docker build -t backend-nginx -f backend/docker/development/nginx/Dockerfile backend/docker

.PHONY: backend-nginx-run
backend-nginx-run:
	docker run --rm --name backend-nginx -e PHP_FPM_ADDRESS=backend-php-fpm:9000 -v ${PWD}/backend/app:/app --network mynetwork -d backend-nginx

.PHONY: backend-nginx-stop
backend-nginx-stop:
	docker stop backend-nginx

.PHONY: php-fpm-build
php-fpm-build:
	docker build -t backend-php-fpm -f backend/docker/development/php-fpm/Dockerfile backend/docker

# run backend server
.PHONY: php-fpm-run
php-fpm-run:
	docker run --rm --name=backend-php-fpm -v ${PWD}/backend/app:/app --network mynetwork -d backend-php-fpm

.PHONY: php-fpm-stop
php-fpm-stop:
	docker stop backend-php-fpm

.PHONY: postgres-run
postgres-run:
	docker run --rm --name=postgres -e POSTGRES_PASSWORD=movie_subtitles_pass -v mv_pgdata:/var/lib/postgresql/data -p 5432:5432 -d postgres:16.2-alpine3.19

.PHONY: postgres-stop
postgres-stop:
	docker stop postgres

.PHONY: php-cli-build
php-cli-build:
	docker build -t backend-php-cli -f backend/docker/development/php-cli/Dockerfile backend/docker

.PHONY: php-cli-run
php-cli-run:
	docker run --rm --name=backend-php-cli -v ${PWD}/backend/app:/app backend-php-cli $(args)

.PHONY: composer-install
composer-install:
	make php-cli-run args="composer install"