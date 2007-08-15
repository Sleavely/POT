all: clean check documentation pdf online test package

clean:
	find . -name "*~" -exec rm {} -v \;
	rm -f documentation.pdf
	rm -rf documentation
	rm -rf online
	rm -rf pot
	rm -f pot.zip

check:
	find . -name "*.php" -exec php -l {} \;

documentation:
	phpdoc -j on -t documentation -o HTML:Smarty:HandS -ti 'PHP OTServ Toolkit' -d . -i test.php

pdf: documentation.pdf

documentation.pdf:
	phpdoc -j on -t . -o PDF:default:default -ti 'PHP OTServ Toolkit' -d . -i test.php

online:
	phpdoc -j on -t online -o HTML:Smarty:OTServAAC -ti 'PHP OTServ Toolkit' -d . -i test.php

test:
	phpunit POTTest test.php

package: pot.zip

pot.zip:
	mkdir pot
	cp BUGS pot
	cp CHANGELOG pot
	cp INSTALL pot
	cp LICENSE pot
	cp NEWS pot
	cp README pot
	cp RULES pot
	cp TODO pot
	cp -r classes pot
	cp -r examples pot
	zip -r pot.zip pot
