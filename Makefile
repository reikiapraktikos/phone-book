init:
	docker-compose build --force-rm --no-cache
	make up
up:
	docker-compose up -d
	docker exec app composer install
	docker exec app bin/console doctrine:database:create --if-not-exists
	docker exec app bin/console doctrine:migrations:migrate
	docker exec app bin/console lexik:jwt:generate-keypair
	docker exec app bin/console doctrine:database:create --env test --if-not-exists
	docker exec app bin/console doctrine:migrations:migrate --env test
	docker exec app bin/console doctrine:fixtures:load --env test --no-interaction
	docker exec app bin/console lexik:jwt:generate-keypair --env test
sh:
	docker exec -it app sh
