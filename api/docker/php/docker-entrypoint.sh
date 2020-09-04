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

	if [ "$APP_ENV" != 'prod' ] && [ -f /certs/localCA.crt ]; then
		ln -sf /certs/localCA.crt /usr/local/share/ca-certificates/localCA.crt
		update-ca-certificates
	fi

	if [ "$APP_ENV" != 'prod' ] && [ "$1" = 'php-fpm' ]; then
		composer install --prefer-dist --no-progress --no-suggest --no-interaction
	fi

	echo "Waiting for db to be ready..."
	ATTEMPTS_LEFT_TO_REACH_DATABASE=60
	until [ $ATTEMPTS_LEFT_TO_REACH_DATABASE -eq 0 ] || bin/console doctrine:query:sql "SELECT 1" > /dev/null 2>&1; do
		sleep 1
		ATTEMPTS_LEFT_TO_REACH_DATABASE=$((ATTEMPTS_LEFT_TO_REACH_DATABASE-1))
		echo "Still waiting for db to be ready... Or maybe the db is not reachable. $ATTEMPTS_LEFT_TO_REACH_DATABASE attempts left"
	done

	if [ $ATTEMPTS_LEFT_TO_REACH_DATABASE -eq 0 ]; then
		echo "The db is not up or not reachable"
		exit 1
	else
	   echo "The db is now ready and reachable"
	fi

	echo "Waiting for event-store to be ready..."
	ATTEMPTS_LEFT_TO_REACH_EVENT_STORE=60
	until [ $ATTEMPTS_LEFT_TO_REACH_EVENT_STORE -eq 0 ] || curl -sf $EVENT_STORE_URL/stats > /dev/null 2>&1; do
		sleep 1
		ATTEMPTS_LEFT_TO_REACH_EVENT_STORE=$((ATTEMPTS_LEFT_TO_REACH_EVENT_STORE-1))
		echo "Still waiting for event-store to be ready... Or maybe the event-store is not reachable. $ATTEMPTS_LEFT_TO_REACH_EVENT_STORE attempts left"
	done

	if [ $ATTEMPTS_LEFT_TO_REACH_EVENT_STORE -eq 0 ]; then
		echo "The event-store is not up or not reachable"
		exit 1
	else
	   echo "The event-store is now ready and reachable"
	fi

	if  [ "$APP_ENV" != 'prod' ] && [ "$1" = 'php-fpm' ]; then
	    bin/console doctrine:schema:update --force --no-interaction
	fi

	if [ -n "$STREAM" ]; then
		curl -sSf -X POST "$EVENT_STORE_URL/projection/\$by_category/command/enable" -H "accept:application/json" -H "Content-Length:0" -u "$EVENT_STORE_CREDENTIALS" > /dev/null
		bin/console app:create-persistent-subscriptions --no-interaction -vvv
	fi
fi

exec docker-php-entrypoint "$@"
