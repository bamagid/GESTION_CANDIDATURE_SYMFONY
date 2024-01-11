sleep 5

php bin/console make:migration --no-interaction
php bin/console doctrine:migrations:migrate --no-interaction

apache2-foreground