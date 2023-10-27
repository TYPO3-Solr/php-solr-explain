#!/usr/bin/env bash

PHAR_BUILD_PATH="/tmp/php-solr-explain"
PHAR_COMPOSER_BINARY="/tmp/phar-composer"

if [[ ! -f $PHAR_COMPOSER_BINARY ]]; then
  wget https://github.com/clue/phar-composer/releases/download/v1.4.0/phar-composer-1.4.0.phar -O $PHAR_COMPOSER_BINARY
  chmod +x $PHAR_COMPOSER_BINARY
fi

rm -Rf $PHAR_BUILD_PATH
mkdir $PHAR_BUILD_PATH
git archive --format=tar --prefix=php-solr-explain/ HEAD | (cd "/tmp" && tar xf -)
composer install --no-dev --working-dir=$PHAR_BUILD_PATH

mkdir -p ".Build/bin"
/tmp/phar-composer build $PHAR_BUILD_PATH ".Build/bin/php-solr-explain.phar"

rm -Rf $PHAR_COMPOSER_BINARY $PHAR_BUILD_PATH
