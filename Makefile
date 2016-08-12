
BABEL ?= node_modules/.bin/babel
UGLIFY ?= node_modules/.bin/uglifyjs
HTML_MINIFIER ?= node_modules/.bin/html-minifier

.PHONY: build
build: www/
build: www/index.php
build: www/ovingsspeilet.js
build: www/ovingsspeilet.html

www/:
	test -d $@ || mkdir $@

www/index.php: ovingsspeilet.php
	cp $< $@

www/ovingsspeilet.js: ovingsspeilet.js
	$(BABEL) \
		$< \
		--presets es2015 \
	| $(UGLIFY) \
		--screw-ie8 \
		--mangle \
		--compress \
	> $@

www/ovingsspeilet.html: ovingsspeilet.html
	$(HTML_MINIFIER) \
		--collapse-boolean-attributes \
		--collapse-inline-tag-whitespace \
		--collapse-whitespace \
		--html5 \
		--minify-css true \
		--remove-attribute-quotes \
		--remove-comments \
		--remove-empty-attributes \
		--remove-optional-tags \
		--remove-script-type-attributes \
		--remove-style-link-type-attributes \
		--remove-tag-whitespace \
		--use-short-doctype \
		$< \
		> $@

.PHONY: server
server: build
	php -S 0.0.0.0:1337 -t www
