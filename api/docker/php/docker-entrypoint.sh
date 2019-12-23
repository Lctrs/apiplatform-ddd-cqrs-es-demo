#!/bin/sh
set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- php-fpm "$@"
fi

if [ "$1" = 'php-fpm' ] || [ "$1" = 'php' ] || [ "$1" = 'bin/console' ]; then
	PHP_INI_RECOMMENDED="$PHP_INI_DIR/php.ini-production"
	if [ "$APP_ENV" != 'prod' ]; then
		PHP_INI_RECOMMENDED="$PHP_INI_DIR/php.ini-development"
	fi
	ln -sf "$PHP_INI_RECOMMENDED" "$PHP_INI_DIR/php.ini"

	mkdir -p var/cache var/log
	setfacl -R -m u:www-data:rwX -m u:"$(whoami)":rwX var
	setfacl -dR -m u:www-data:rwX -m u:"$(whoami)":rwX var

	if [ "$APP_ENV" != 'prod' ] && [ "$1" = 'php-fpm' ]; then
		composer install --prefer-dist --no-progress --no-suggest --no-interaction
	fi

	echo "Waiting for db to be ready..."
	until bin/console doctrine:query:sql "SELECT 1" > /dev/null 2>&1; do
		sleep 1
	done

	if  [ "$APP_ENV" != 'prod' ] && [ "$1" = 'php-fpm' ]; then
	    bin/console doctrine:schema:update --force --no-interaction
	fi

	echo "Waiting for event-store to be ready..."
	until curl -sf $EVENT_STORE_URL/stats > /dev/null 2>&1; do
		sleep 1
	done

	if [ -n "$STREAM" ]; then
		curl -sSf -X POST "$EVENT_STORE_URL/projection/\$by_category/command/enable" -H "accept:application/json" -H "Content-Length:0" -u "$EVENT_STORE_CREDENTIALS" > /dev/null
		bin/console app:create-persistent-subscriptions --no-interaction -vvv
	fi
fi

exec docker-php-entrypoint "$@"
