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
	docker run --rm -d --name gateway -p 8080:8080 gateway

# stop gateway
.PHONY: gateway-stop
gateway-stop:
	docker stop gateway

# build backend
.PHONY: backend-build
backend-build:
	composer install -d ${PWD}/backend/app

# run backend server
.PHONY: backend-run
backend-run:
	symfony server:start --dir=${PWD}/backend/app -d --port=8081

.PHONY: backend-stop
backend-stop:
	symfony server:stop --dir=${PWD}/backend/app

.PHONY: postgresql-run
postgresql-run:
	docker run --rm --name=postgres -e POSTGRES_PASSWORD=movie_subtitles_pass -v mv_pgdata:/var/lib/data -p 5432:5432 -d postgres:16.2-alpine3.19

.PHONY: postgresql-stop
postgresql-stop:
	docker stop postgres