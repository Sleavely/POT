all: clean check documentation pdf otserv-aac test

clean:
	find . -name "*~" -exec rm {} -v \;
	rm -f documentation.pdf
	rm -rf documentation
	rm -rf pot

check:
	find . -name "*.php" -exec php -l {} \;

documentation:
	phpdoc -j on -t documentation -o HTML:Smarty:HandS -ti POT -d . -i test.php

pdf: documentation.pdf

documentation.pdf:
	phpdoc -j on -t . -o PDF:default:default -ti POT -d . -i test.php

otserv-aac: pot

pot:
	phpdoc -j on -t pot -o HTML:Smarty:OTServAAC -ti POT -d . -i test.php

test:
	phpunit POTTest test.php
