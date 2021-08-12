start:
	php artisan serve --host 0.0.0.0
console:
	php artisan tinker
log:
	tail -f storage/logs/laravel.log
deploy:
	git push heroku
lint:
	composer run-script phpcs -- --standard=PSR12
lint-fix:
	composer exec --verbose phpcbf -- --standard=PSR12
