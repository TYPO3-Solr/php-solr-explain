#!/usr/bin/env bash

SCRIPTPATH=$( cd $(dirname ${BASH_SOURCE[0]}) ; pwd -P )
ROOTPATH="$SCRIPTPATH/../../"

cd $ROOTPATH
composer install