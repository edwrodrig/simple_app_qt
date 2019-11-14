<?php

require_once __DIR__ . '/common.php';

chdir(__DIR__);

$sourceDirname = Common::appVersionName();
$targetFilename = $sourceDirname . '.tar.gz';

if ( file_exists($targetFilename) ) {
	unlink($targetFilename);
}

if ( !is_dir($sourceDirname) ) {
	fprintf(STDERR, "[%s] is not a deploy directory\n", $sourceDirname);
	exit(1);
}

passthru(sprintf(
	"tar -zcvf %s %s",
	$targetFilename,
	$sourceDirname
));

printf("Tar file generated at [%s]\n", $targetFilename);









