default:
	echo Targets: release

release:
	release=phptop-`./phptop --version | sed 's/phptop[^ ]* //'`; \
	mkdir -p $$release && ( \
	  cp phptop phptop_hook.php phptop.1 COPYING README TODO NEWS $$release; \
	  tar czf $$release.tar.gz $$release; \
	  rm -rf $$release )

deb:
	@echo "Don't forget to edit debian/changelog..."
	@sleep 2
	@echo "Building the package..."
	dpkg-buildpackage -rfakeroot -uc -us

debclean:
	fakeroot debian/rules clean
	rm build

debupload:
	rsync -z ../phptop_*.deb builder@deb.bearstech.com:~/src/phptop/
	ssh builder@deb.bearstech.com make -C www phptop

deploy:
	foreach -e 'dpkg -l phptop 2>/dev/null|egrep ^i >/dev/null && aptitude update >/dev/null && apt-get --force-yes -y -q=2 install phptop'
