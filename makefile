# defines variables
#include Make.config

run:
	docker-compose up -d 
	docker-compose exec webapi php artisan migrate


test:
	docker-compose exec webapi php artisan test

showlogs:
	echo "Showing logs...... \n " && tail -f src/storage/logs/laravel.log

permissions:
	sudo find src/ -type d -exec chmod 775 {} \;
	sudo find src/ -type f -exec chmod 664 {} \;
	sudo chown -R www-data:${USER} src
