test:
	php artisan test
install:
	composer install
lint:
	composer phpcs -- --standard=PSR12 routes app/Http/Controllers tests
lint-fix:
	composer phpcbf -- --standard=PSR12 routes app/Http/Controllers tests
