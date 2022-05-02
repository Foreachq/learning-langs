docker-up: docker-down
	@touch .docker/.zsh_history
	docker-compose up -d --build

docker-down:
	docker-compose down

docker-restart: docker-up

lint:
	composer run-script lint

static-analysis:
	composer run-script static-analysis
