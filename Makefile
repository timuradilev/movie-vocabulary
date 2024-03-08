# build
.PHONE: frontend-build
frontend-build:
	docker build -t frontend -f frontend/docker/development/Dockerfile ${PWD}/frontend/app

# run
.PHONY: frontend-run
frontend-run:
	docker run --rm -d --name frontend -v ${PWD}/frontend/app:/app -p 5173:5173 frontend sh -c "npm i ; npm run dev -- --host"

# install npm packages
.PHONE: frontend-npm-install
frontend-npm-install:
	docker run --rm -v ${PWD}/frontend/app:/app frontend npm i