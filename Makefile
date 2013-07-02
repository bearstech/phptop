default:
	echo Targets: release deb debclean

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
