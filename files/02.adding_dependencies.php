<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'common.php';

$os = Common::operativeSystem();
$release_data = Common::releaseData();
$build_dir = Common::compilationDirName();
$qt_dir = Common::qtDir();

$release_binary =
	$build_dir
		. DIRECTORY_SEPARATOR
		. $release_data['binary_name'][$os];

if ( !file_exists($release_binary) ) {
	fprintf(STDERR, "Binary [%s] does not exists\nPlease run previous step [01.build_binary.php]\n", $release_binary);
	exit(1);
}

printf("Binary found at [%s]\n", $release_binary);

$target_dir = Common::buildDirName();

if ( is_dir($target_dir) ) {
	passthru(sprintf("rm -rf %s", $target_dir));
}

$target_binary = $target_dir . DIRECTORY_SEPARATOR . basename($release_data['binary_name'][$os]);
printf("Creating build target dir [%s]...\n", $target_dir);
mkdir($target_dir);
copy(
	$release_binary,
	$target_binary
);
chdir($target_dir);

printf("Retrieving dependencies...\n");

if ( $os == 'linux'  ) {
	$lib_dir = $qt_dir;
	exec(sprintf("ldd -r %s", $target_binary), $output, $return_var);
	foreach ( $output as $line ) {
		$tokens = explode("=>", $line);
		$final_token = trim($tokens[1] ?? $tokens[0]);
		$tokens = explode(" ", $final_token);
		$lib = $tokens[0] ?? null;
		if ( is_null($lib) ) continue;
		if ( !file_exists($lib) ) continue;
		if ( strpos($lib, "/Qt/") !== FALSE ) {
			
			printf("\t[%s]\n", $lib);
			copy($lib, $target_dir . DIRECTORY_SEPARATOR . basename($lib));
		}
	}

        /**
        * libQtDBus and libQt5XcbQpa  is needed for plugin platforms/libqxcb.so
        * Failed to load platform plugin "xcb". Available platforms are:
        */
	$additional_libs = [
		$qt_dir . "/lib/libQt5DBus.so.5",
		$qt_dir . "/lib/libQt5XcbQpa.so.5"
	];	

	foreach ( $additional_libs as $lib ) {
		printf("\t[%s]\n", $lib);
		copy($lib, $target_dir . DIRECTORY_SEPARATOR . basename($lib));
	}
		

	printf("Retrieving plugins..\n");
	$plugins = [
		"audio",
		"bearer",
		"iconengines",
		"imageformats",
		"mediaservice",
		"platforms",
		"playlistformats",
		"sqldrivers"
	];
	foreach ( $plugins as $plugin ) {
		printf("\t[%s]\n", $plugin);
		passthru(sprintf(
			"cp -rf %s %s",
			$qt_dir . "/plugins/" . $plugin,
			 $target_dir
		));
		passthru(sprintf(
			"rm -f %s",
			$target_dir . DIRECTORY_SEPARATOR . $plugin . DIRECTORY_SEPARATOR . "*.debug"
		));
		
	}

	file_put_contents($target_dir . DIRECTORY_SEPARATOR . "run_tundra.sh", <<<'EOF'
#!/bin/sh
appname=tundra

dirname=`dirname $0`
tmp="${dirname#?}"

if [ "${dirname%$tmp}" != "/" ]; then
dirname=$PWD/$dirname
fi
LD_LIBRARY_PATH=$dirname
export LD_LIBRARY_PATH
$dirname/$appname "$@"
EOF
	);

	chmod($target_dir . DIRECTORY_SEPARATOR . "run_tundra.sh", 0755);
	chmod($target_dir . DIRECTORY_SEPARATOR . "tundra", 0755);


} else if ( $os == 'win32' ) {
	passthru(sprintf(
		'%s %s %s',
		'windeployqt',
		$target_dir . DIRECTORY_SEPARATOR . Common::binaryFilename()
	));
				

}







