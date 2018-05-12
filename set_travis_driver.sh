#!/usr/bin/env bash

echo db_driver: "$1" >> settings/credentials.local.travis.yml
cat settings/credentials.local.travis.yml
