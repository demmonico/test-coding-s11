#!/usr/bin/env bash

_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

mysql -p$MYSQL_ROOT_PASSWORD $MYSQL_DATABASE < ${_DIR}/init.sql
