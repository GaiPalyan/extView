start:
	php artisan serve --host 0.0.0.0
setup:
	composer install
	npm install
	cp -n .env.example .env || true
	php artisan key:gen --ansi
	npm ci
watch:
	npm run watch
migrate:
	php artisan migrate
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
test:
	php artisan test
