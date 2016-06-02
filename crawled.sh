#!/bin/sh
composer dump-autoload && php artisan migrate:refresh --seed && php artisan crawler:run -v

