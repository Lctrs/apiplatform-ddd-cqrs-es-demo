#!/bin/sh

docker-compose build --pull
docker-compose run admin yarn upgrade
docker-compose run client yarn upgrade
docker-compose run php composer update
echo 'Run `docker-compose up --build --force-recreate` now and check that everything is fine!'
