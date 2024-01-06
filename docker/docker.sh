sleep 10
php bin/console doctrine:database:create --if-not-exists --no-interaction
php bin/console doctrine:migration --no-interaction
php bin/console doctrine:migrations:migrate --no-interaction

apache2-foreground

