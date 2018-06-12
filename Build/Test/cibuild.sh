#!/usr/bin/env bash

SCRIPTPATH=$( cd $(dirname ${BASH_SOURCE[0]}) ; pwd -P )
ROOTPATH="$SCRIPTPATH/../../"

cd $ROOTPATH
./.Build/bin/phpunit -c Build/Test/phpunit.xml