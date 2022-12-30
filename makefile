# defines variables
#include Make.config

make:
	docker-compose up -d 
	docker-compose exec webapi php artisan migrate


test:
	docker-compose exec webapi php artisan test