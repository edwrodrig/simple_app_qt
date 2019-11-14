<?php

class Common {

private static $releaseData = null;

private static $operativeSystem = null;

public static function releaseData() {
	if ( is_null(self::$releaseData) ) {
		printf("Reading release data...\n");
		self::$releaseData = json_decode(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . "release_data.json"), true);
	}
	return self::$releaseData;
}

public static function operativeSystem() {
	if ( is_null(self::$operativeSystem) ) {
		self::$operativeSystem = strtolower(php_uname('s'));
	}
	return self::$operativeSystem;
}

public static function appVersionName() {
	return
		self::executableName()
		. '-'
		. str_replace('.', '_', self::releaseData()['version'])
		. '-'
		. self::operativeSystem();
}

public static function compilationDirName() {
	return __DIR__ . DIRECTORY_SEPARATOR . 'compilation';
}

public static function buildDirName() {
	return
		__DIR__ . DIRECTORY_SEPARATOR . self::appVersionName();
}

public static function qtDir() {
	if ( self::operativeSystem() === 'linux' ) {
		return
			"/home/edwin/Qt/5.13.0/gcc_64";
	} else {
		fprintf(STDERR, "No qt dir for this operative system [%s]", self::operativeSystem());
		exit(1);
	}
}

public static function executableName() {
	return self::$releaseData['executable_name'];
}

public static function executableDir() {
	return self::$releaseData['executable_dir'];
}

public static function binaryFilename() {
	if ( self::operativeSystem() === 'win32' ) {
		return self::executableName() . ".exe";
	} else {
		return self::executableName();
	}
}

public static function linuxRunScriptFilename() {
	return "run_" . self::executableName() . ".sh";
}



}

