all: clean check documentation pdf test

clean:
	find . -name "*~" -exec rm {} -v \;
	rm -f documentation.pdf
	rm -rf documentation

check:
	find . -name "*.php" -exec php -l {} \;

documentation:
	phpdoc -j on -t documentation -o HTML:Smarty:HandS -ti POT -d . -i test.php

pdf: documentation.pdf

documentation.pdf:
	phpdoc -j on -t . -o PDF:default:default -ti POT -d . -i test.php

test:
	phpunit POTTest test.php
