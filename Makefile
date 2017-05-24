# customization

PACKAGE_NAME = icanboogie/storage
PACKAGE_VERSION = 2.1.0
PHPUNIT_VERSION = phpunit-4.8.phar
PHPUNIT_FILENAME = build/$(PHPUNIT_VERSION)
PHPUNIT = php $(PHPUNIT_FILENAME)

# do not edit the following lines

usage:
	@echo "test:  Runs the test suite.\ndoc:   Creates the documentation.\nclean: Removes the documentation, the dependencies and the Composer files."

vendor:
	@composer install

update:
	@composer update

autoload: vendor
	@composer dump-autoload

$(PHPUNIT_FILENAME):
	mkdir -p build
	wget https://phar.phpunit.de/$(PHPUNIT_VERSION) -O $(PHPUNIT_FILENAME)

test-dependencies: $(PHPUNIT_FILENAME) vendor

test: test-dependencies
	@rm -Rf tests/sandbox/*
	@$(PHPUNIT)

test-coverage: test-dependencies
	@mkdir -p build/coverage
	@$(PHPUNIT) --coverage-html build/coverage

test-coveralls: test-dependencies
	@mkdir -p build/logs
	COMPOSER_ROOT_VERSION=$(PACKAGE_VERSION) composer require satooshi/php-coveralls
	@$(PHPUNIT) --coverage-clover build/logs/clover.xml
	php vendor/bin/coveralls -v

doc: vendor
	@mkdir -p build/docs
	@apigen generate \
	--source lib \
	--destination build/docs/ \
	--title "$(PACKAGE_NAME) $(PACKAGE_VERSION)" \
	--template-theme "bootstrap"

clean:
	@rm -fR build
	@rm -fR vendor
	@rm -f composer.lock

.PHONY: all autoload doc clean test test-coverage test-coveralls test-dependencies update
