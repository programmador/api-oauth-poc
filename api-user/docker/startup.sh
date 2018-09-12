#!/bin/bash
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
php vendor/bin/codecept run api
apache2-foreground