default:
	echo Targets: release

release:
	release=phptop-`./phptop --version | sed 's/phptop[^ ]* //'`; \
	mkdir -p $$release && ( \
	  cp phptop phptop_hook.php phptop.1 COPYING README TODO NEWS $$release; \
	  tar czf $$release.tar.gz $$release; \
	  rm -rf $$release )

deb:
	dpkg-buildpackage -rfakeroot -i -I.svn -I'*.log' -uc -us

debclean:
	fakeroot debian/rules clean
	rm build

bearstech-build:
	ssh builder@melba  'cd src/phptop/phptop-svn && svn up && make deb && make -C /var/www/deb.bearstech.com/lenny phptop'
	ssh builder@rupert 'cd phptop/phptop-svn && svn up && build && upload phptop'

bearstech-deploy:
	foreach -e 'dpkg -l phptop 2>/dev/null|egrep ^i >/dev/null && aptitude update >/dev/null && apt-get --force-yes -y -q=2 install phptop'
