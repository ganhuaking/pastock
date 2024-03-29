#!/usr/bin/make -f

.PHONY: all clean clean-all check test analyse coverage

# ---------------------------------------------------------------------

all: test analyse

clean:
	git clean -Xfq bootstrap build

clean-all: clean
	rm -rf ./vendor
	rm -rf ./composer.lock

check:
	php vendor/bin/phpcs

test: clean check
	phpdbg -qrr vendor/bin/phpunit

analyse:
	php vendor/bin/phpstan analyse src --level=max

coverage: test
	@if [ "`uname`" = "Darwin" ]; then open build/coverage/index.html; fi

image:
	docker build -t pastock .

run:
	docker run --rm -it pastock stock:now

debug:
	heroku config:set APP_DEBUG=true LOG_LEVEL=debug

prod:
	heroku config:set APP_DEBUG=false LOG_LEVEL=info

value:
	php pastock stock:pers --filter-per-lt=13 --filter-pbr-lt=0.7