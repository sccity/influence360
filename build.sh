#!/bin/bash

if [ -z "$1" ]; then
  echo "Usage: $0 <version>"
  exit 1
fi

VERSION=$1
composer dump-autoload
./clean.sh
rm -fR storage/cache/*
rm -fR storage/framework/views/*
rm -fR storage/framework/sessions/*
rm -fR storage/app/public/*
rm -fR storage/logs/*
docker build --platform linux/x86_64 -t sccity/influence360:$VERSION --push .
