all: clean check documentation pdf online test package manual

clean:
	find . -name "*~" -exec rm {} -v \;
	rm -f documentation.pdf
	rm -rf documentation
	rm -rf online
	rm -rf pot
	rm -f pot.tar.gz

check:
	find . -name "*.php" -exec php -l {} \;

documentation:
	phpdoc -j on -t documentation -o HTML:Smarty:HandS -ti 'PHP OTServ Toolkit' -d . -i test.php -ric CHANGELOG,INSTALL,LICENSE,NEWS,README,RULES

pdf: documentation.pdf

documentation.pdf:
	phpdoc -j on -t . -o PDF:default:default -ti 'PHP OTServ Toolkit' -d . -i test.php -ric CHANGELOG,INSTALL,LICENSE,NEWS,README,RULES

online:
	phpdoc -j on -t online -o HTML:Smarty:OTServAAC -ti 'PHP OTServ Toolkit' -d . -i test.php -ric CHANGELOG,INSTALL,LICENSE,NEWS,README,RULES

test:
	phpunit POTTest test.php

package: pot.tar.gz

pot.tar.gz:
	mkdir pot
	cp ../../otserv/trunk/AUTHORS pot
	cp CHANGELOG pot
	cp INSTALL pot
	cp LICENSE pot
	cp LICENSE.PEAR.CRYPT_RSA pot
	cp LICENSE.PEAR.CRYPT_XTEA pot
	cp NEWS pot
	cp README pot
	cp RULES pot
	cp compat.php pot
	cp -r classes pot/pot
	rm -rf pot/pot/.svn
	tar -zcf pot.tar.gz pot/*

manual: documentation.tar.gz

documentation.tar.gz: documentation.pdf
	tar -zcf documentation.tar.gz documentation.pdf
