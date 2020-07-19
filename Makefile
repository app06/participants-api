init: composer-install make-migr make-seed make-passport

composer-install:
	docker-compose run --rm php-cli composer install

make-migr:
	docker-compose run --rm php-cli php artisan migrate

make-seed:
	docker-compose run --rm php-cli php artisan db:seed

make-passport:
	docker-compose run --rm php-cli php artisan passport:install

test:
	docker-compose run --rm php-cli php artisan test

watch:
	docker-compose run --rm php-cli php artisan queue:watch
