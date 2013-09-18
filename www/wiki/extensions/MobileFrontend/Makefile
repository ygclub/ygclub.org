MW_INSTALL_PATH ?= ../..

.PHONY: less

remotes:
	wget https://raw.github.com/browserstate/history.js/master/scripts/uncompressed/history.adapter.jquery.js -O javascripts/externals/history.adapter.jquery.js --no-check-certificate
	wget https://raw.github.com/browserstate/history.js/master/scripts/uncompressed/history.js -O javascripts/externals/history.js --no-check-certificate

nodecheck:
	@scripts/nodecheck.sh

jshinttests: nodecheck
	@node_modules/.bin/jshint tests/javascripts/* --config .jshintrc

jshint: nodecheck jshinttests
	@node_modules/.bin/jshint javascripts/* --config .jshintrc

less: nodecheck
	@node_modules/.bin/autoless --no-watch less/ stylesheets/

lesswatch: nodecheck
	@node_modules/.bin/autoless less/ stylesheets/

phpunit:
	cd ${MW_INSTALL_PATH}/tests/phpunit && php phpunit.php --configuration ${MW_INSTALL_PATH}/extensions/MobileFrontend/tests/mfe.suite.xml --group=MobileFrontend

qunit:
	@scripts/qunit.sh

qunitdebug:
	@scripts/qunit.sh 'MobileFrontend&debug=true'

tests: jshint phpunit qunit

cucumber:
	@scripts/cucumber.sh

installhooks:
	ln -sf ${PWD}/scripts/pre-commit .git/hooks/pre-commit
	ln -sf ${PWD}/scripts/pre-rebase .git/hooks/pre-rebase
	ln -sf ${PWD}/scripts/post-rewrite .git/hooks/post-rewrite

# user must create W3CValidationTest wiki page with text 'Hello world' for this to work
validatehtml:
	@scripts/validatehtml.sh
