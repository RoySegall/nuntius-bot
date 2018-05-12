#!/usr/bin/env bash

# Install MongoDB for PHP.
# source: https://gist.github.com/gyndav/2351174.
wget http://pecl.php.net/get/mongodb-1.4.3.tgz
tar -xzf mongodb-1.4.3.tgz
sh -c "cd mongodb-1.4.3 && pecl install mongodb"
echo "extension=mongodb.so" >> `php --ini | grep "Loaded Configuration" | sed -e "s|.*:\s*||"`
