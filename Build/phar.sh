#!/usr/bin/env bash

SCRIPTPATH=$( cd $(dirname ${BASH_SOURCE[0]}) ; pwd -P )
ROOTPATH="$SCRIPTPATH/../"

cd $ROOTPATH
rm -fR .Build
composer install --no-dev

cd ..
wget https://github.com/clue/phar-composer/releases/download/v1.0.0/phar-composer.phar
php phar-composer.phar build "$ROOTPATH/../php-solr-explain"