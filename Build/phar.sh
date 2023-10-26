#!/usr/bin/env bash

SCRIPT_PATH=$( cd $(dirname "${BASH_SOURCE[0]}"); pwd -P )
ROOT_PATH="$SCRIPT_PATH/../"

cd "$ROOT_PATH"
rm -fR .Build
composer install --no-dev

cd ..
wget https://github.com/clue/phar-composer/releases/download/v1.4.0/phar-composer-1.4.0.phar -O phar-composer
php phar-composer build "$ROOT_PATH/../php-solr-explain"
