#!/bin/sh
composer dump-autoload && php artisan migrate:refresh --seed -v && php artisan crawler:run -v

