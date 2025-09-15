release: php artisan migrate --force
web: vendor/bin/heroku-php-apache2 public/
queue: php artisan queue:work --sleep=3 --tries=3 --timeout=90
reverb: php artisan reverb:start --port=6001 --host=0.0.0.0
