<?php

namespace edwrodrig\qt_app_builder;

class Common
{

    private static $releaseData = null;

    private static $operativeSystem = null;

    private static $qtDirectory = null;

    public static function releaseData()
    {
        if (is_null(self::$releaseData)) {
            printf("Reading release data...\n");
            self::$releaseData = json_decode(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR), true);
        }
        return self::$releaseData;
    }

    public static function operativeSystem()
    {
        if (is_null(self::$operativeSystem)) {
            self::$operativeSystem = strtolower(php_uname('s'));
        }
        return self::$operativeSystem;
    }

    public static function appVersionName()
    {
        return
            self::executableName()
            . '-'
            . str_replace('.', '_', self::releaseData()['version'])
            . '-'
            . self::operativeSystem();
    }

    /**
     *
     * @return string
     */
    public static function compilationDirName()
    {
        return __DIR__ . DIRECTORY_SEPARATOR;
    }

    public static function buildDirName()
    {
        return
            __DIR__ . DIRECTORY_SEPARATOR . self::appVersionName();
    }

    public static function qtDir()
    {
        if (self::operativeSystem() === 'linux') {
            return
                "/home/edwin/Qt/5.13.0/gcc_64";
        } else {
            fprintf(STDERR, "No qt dir for this operative system [%s]", self::operativeSystem());
            exit(1);
        }
    }

    public static function findOperativeSystem() : bool {
        self::$operativeSystem = strtolower(php_uname('s'));
        fprintf(STDOUT,"Operative system [%s]\n", self::$operativeSystem);
        return true;
    }

    public static function getOperativeSystem() : string {
        if ( is_null(self::$operativeSystem) ) {
            self::findOperativeSystem();
        }
        return self::$operativeSystem;
    }

    public static function findQtDirectory() : bool {
        if ( self::getOperativeSystem() === 'linux' ) {
            self::$qtDirectory = "/home/edwin/Qt/5.13.0/gcc_64";
            fprintf(STDOUT,"Qt directory [%s]\n", self::$qtDirectory);
            return true;
        } else {
            fprintf(STDERR, "Qt directory not found!\n");
            return false;
        }
    }

    public static function getQtDirectory() : string {
        if ( is_null(self::$qtDirectory) ) {
            self::findQtDirectory();
        }
        return self::$qtDirectory;
    }

    public static function executableName()
    {
        return self::$releaseData['executable_name'];
    }

    public static function executableDir()
    {
        return self::$releaseData['executable_dir'];
    }

    public static function binaryFilename()
    {
        if (self::operativeSystem() === 'win32') {
            return self::executableName() . ".exe";
        } else {
            return self::executableName();
        }
    }

    /**`
     * @return string
     */
    public static function linuxRunScriptFilename() : string
    {
        return "run_" . self::executableName() . ".sh";
    }

    public static function findQmake() : string {
        $qmake_bin = $qt_dir . "/bin/qmake";
        if ( !file_exists($qmake_bin) ) {
            fprintf(STDERR, "QMake does not exists at [%s]\n", $qmake_bin);
            exit(1);
        }
    }


}

