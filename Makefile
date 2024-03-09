# build frontend
.PHONE: frontend-build
frontend-build:
	docker build -t frontend -f frontend/docker/development/Dockerfile ${PWD}/frontend/app

# run frontend
.PHONY: frontend-run
frontend-run:
	docker run --rm -d --name frontend -v ${PWD}/frontend/app:/app -p 5173:5173 frontend sh -c "npm i ; npm run dev -- --host"

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

# build backend
.PHONY: backend-build
backend-build:
	composer install -d ${PWD}/backend/app

# run backend server
.PHONY: backend-run
backend-run:
	symfony server:start --dir=${PWD}/backend/app -d

.PHONY: backend-stop
backend-stop:
	symfony server:stop --dir=${PWD}/backend/app