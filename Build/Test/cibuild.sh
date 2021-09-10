#!/usr/bin/env bash

SCRIPT_PATH=$( cd $(dirname "${BASH_SOURCE[0]}"); pwd -P )
ROOT_PATH="$SCRIPT_PATH/../../"

cd "$ROOT_PATH"
if ! .Build/bin/phpunit --config Build/Test/phpunit.xml --colors --coverage-clover=coverage.unit.clover; then
  echo "There are failed unit tests. Please fix the issues."
  exit 1;
fi