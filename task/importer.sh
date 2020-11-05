#!/usr/bin/env bash

URL=$1
if [ -z "${URL}" ]; then
  echo "Error: URL param is required"
  exit 1;
fi

_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

cat ${_DIR}/users.csv | tail -n +2 | tr '\r' ' ' | while IFS=';' read -r username phone
do
  phone="$( echo "${phone}" | sed 's/[^+0-9]*//g' )"
  echo "Fetched from CSV: username='${username}' phone='${phone}'"
  if [ -n "${username}" ]; then
    curl -sS -X PUT --data-urlencode "phone=${phone}" "${URL}/user/${username}"
    echo ''
  fi
done
