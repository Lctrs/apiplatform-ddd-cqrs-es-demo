#!/usr/bin/env bash

set -e
set +v

if ! type heroku > /dev/null; then
  curl https://cli-assets.heroku.com/install.sh | sh
fi

heroku login
heroku git:remote -a api-es-demo
git subtree push --prefix api heroku master
