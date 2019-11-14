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


