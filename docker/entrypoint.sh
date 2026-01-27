#!/usr/bin/env bash
set -e

php-fpm &
nginx -g "daemon off;"

