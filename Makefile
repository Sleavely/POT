all: clean check documentation pdf online test package manual phk phar

clean:
	find . -name "*~" -exec rm {} -v \;
	rm -f documentation.pdf
	rm -rf documentation
	rm -rf online
	rm -rf pot
	rm -f POT.phk
	rm -f POT.phar
	rm -f pot.tar.gz
	rm -f manual.tar.gz
	rm -f pot-phk.tar.gz
	rm -f pot-phar.tar.gz

check:
	find . -name "*.php" -exec php -l {} \;

documentation:
	phpdoc -j on -t documentation -o HTML:Smarty:HandS -ti 'PHP OTServ Toolkit' -d . -i test.php,examples/,phk/,phar.php -ric CHANGELOG,INSTALL,LICENSE,NEWS,README,RULES

pdf: documentation.pdf

documentation.pdf:
	phpdoc -j on -t . -o PDF:default:default -ti 'PHP OTServ Toolkit' -d . -i test.php,examples/,phk/,phar.php -ric CHANGELOG,INSTALL,LICENSE,NEWS,README,RULES

online:
	phpdoc -j on -t online -o HTML:Smarty:OTServAAC -ti 'PHP OTServ Toolkit' -d . -i test.php,examples/,phk/,phar.php -ric CHANGELOG,INSTALL,LICENSE,NEWS,README,RULES
	find online -name "*.html" | sed s/"^online"/"http:\/\/otserv-aac.info"/ > online/sitemap

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

phk: pot-phk.tar.gz

pot-phk.tar.gz: POT.phk
	tar -zcf pot-phk.tar.gz POT.phk

POT.phk:
	php /usr/bin/PHK_Creator.phk build POT.phk POT.psf

phar: pot-phar.tar.gz

pot-phar.tar.gz: POT.phar
	tar -zcf pot-phar.tar.gz POT.phar

POT.phar:
	php phar.php
