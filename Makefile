init:
	docker-compose build --force-rm --no-cache
up:
	docker-compose up -d
sh:
	docker exec -it app sh
