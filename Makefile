start:
	php artisan serve --host 0.0.0.0
console:
	php artisan tinker
log:
	tail -f storage/logs/laravel.log
deploy:
	git push heroku
lint:
	composer phpcs
lint-fix:
	composer phpcbf
install:
	composer install
