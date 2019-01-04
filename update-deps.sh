#!/bin/sh

docker-compose pull
docker-compose build --pull
docker-compose run admin /bin/sh -c 'yarn install && yarn upgrade'
docker-compose run client /bin/sh -c 'yarn install && yarn upgrade'
docker-compose run php composer update
echo 'Run `docker-compose up --build --force-recreate` now and check that everything is fine!'
