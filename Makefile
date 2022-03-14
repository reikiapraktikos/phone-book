init:
	docker-compose build --force-rm --no-cache
up:
	docker-compose up -d
	docker exec app composer install
	docker exec app bin/console doctrine:database:create --if-not-exists
	docker exec app bin/console doctrine:migrations:migrate
sh:
	docker exec -it app sh
