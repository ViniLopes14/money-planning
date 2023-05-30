#!/bin/bash

composer install
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate --no-interaction
cd GeneratePDFScript && yarn && cd ..

php-fpm --nodaemonize
