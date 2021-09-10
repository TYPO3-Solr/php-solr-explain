#!/usr/bin/env bash

SCRIPT_PATH=$( cd $(dirname "${BASH_SOURCE[0]}"); pwd -P )
ROOT_PATH="$SCRIPT_PATH/../../"

cd "$ROOT_PATH"
if ! composer install; then
  echo "Could not install composer dependencies. Please fix that issue first."
  exit 1;
fi

echo "The environment is ready."