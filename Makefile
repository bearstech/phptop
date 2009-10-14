default:
	echo Targets: release

release:
	release=phptop-`./phptop --version | sed 's/phptop[^ ]* //'`; \
	mkdir -p $$release && ( \
	  cp phptop phptop_hook.php COPYING README TODO $$release; \
	  tar czf $$release.tar.gz $$release; \
	  rm -rf $$release )
