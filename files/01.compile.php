<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use edwrodrig\qt_app_builder\Common;

$os = Common::operativeSystem();
$release_data = Common::releaseData();
$build_dir = Common::compilationDirName();
$qt_dir = Common::qtDir();

$home = getenv('HOME');
if ( empty($home) ) {
	fprintf(STDERR, "Home environment var does not exists\n");
	exit(1);
}

$home = rtrim($home, DIRECTORY_SEPARATOR);

$qmake_bin = $qt_dir . "/bin/qmake";
if ( !file_exists($qmake_bin) ) {
	fprintf(STDERR, "QMake does not exists at [%s]\n", $qmake_bin);
	exit(1);
}
printf("Qmake found at [%s]\n", $qmake_bin);


$target_dir = $build_dir;
if ( is_dir($target_dir) ) {
	passthru(sprintf("rm -rf %s", $target_dir));
}

printf("Creating build target dir [%s]...\n", $target_dir);
mkdir($target_dir);
chdir($target_dir);

printf("Calling qmake...\n");
passthru(sprintf("%s -config release \"CONFIG-=debug_and_release_folder\" %s",
	$qmake_bin,
	__DIR__
    . DIRECTORY_SEPARATOR
		. DIRECTORY_SEPARATOR
		. $release_data["qt_project_file"]
	), $return_var);

if ( $return_var != 0 ) {
  fprintf(STDERR, "Error generation Makefile with Qmake [%s]". $qmake_bin);
}

printf("Calling make...\n");
passthru("make", $return_var);
if ( $return_var != 0 ) {
	fprintf(STDERR, "Error at calling make to Makefile");
}

if ( $os == "linux" ) {

	$binaryFilePath = $target_dir
                . DIRECTORY_SEPARATOR
                . Common::executableDir()
                . DIRECTORY_SEPARATOR
                . Common::binaryFilename();

	printf("Changing execution mode of [$binaryFilePath]...\n");
	chmod($binaryFilePath , 0755);

}



