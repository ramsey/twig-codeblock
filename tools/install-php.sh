#!/bin/bash
#
# Installs Travis CI PHP into a Travis CI Python environment
#

if [ -z "$TRAVIS" ]; then
    >&2 echo 'ERROR: This script may only run inside the Travis CI environment'
    exit 1
fi

archive_url="https://s3.amazonaws.com/travis-php-archives/binaries/ubuntu/12.04/x86_64/php-${PHP_VERSION}.tar.bz2"

# Install phpenv
type phpenv >/dev/null 2>&1
if [ $? -ne 0 ]; then
    git clone git://github.com/CHH/phpenv.git $HOME/phpenv.git
    $HOME/phpenv.git/bin/phpenv-install.sh
    PATH="$HOME/.phpenv/bin:$HOME/.phpenv/shims:$PATH"
    eval "$(phpenv init -)"
fi

phpenv rehash

# Install PHP
phpenv global $PHP_VERSION 2>/dev/null
if [ $? -ne 0 ]; then
    curl -s -o archive.tar.bz2 $archive_url && tar xjf archive.tar.bz2 --directory /
    phpenv rehash
    phpenv global $PHP_VERSION
fi

if [ $? -ne 0 ]; then
    >&2 echo 'ERROR: PHP was not properly installed'
    exit 1
fi

# Install Composer
cd $TRAVIS_BUILD_DIR
EXPECTED_SIGNATURE=$(wget -q -O - https://composer.github.io/installer.sig)
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
ACTUAL_SIGNATURE=$(php -r "echo hash_file('SHA384', 'composer-setup.php');")

if [ "$EXPECTED_SIGNATURE" != "$ACTUAL_SIGNATURE" ]; then
    >&2 echo 'ERROR: Invalid installer signature'
    exit 1
fi

php composer-setup.php --quiet
if [ $? -ne 0 ]; then
    >&2 echo 'ERROR: Unable to set up Composer'
    exit 1
fi

rm composer-setup.php