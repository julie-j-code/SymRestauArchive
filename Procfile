release: php bin/console doctrine:migrations:migrate && php bin/console cache:clear && php bin/console cache:warmup
web: heroku-php-apache2 public/