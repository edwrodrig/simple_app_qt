<?php
declare(strict_types=1);

namespace edwrodrig\qt_app_builder\variable;

class Variables
{

    private static $operativeSystem = null;

    public static function operativeSystem() : OperativeSystem {
        if ( is_null(self::$operativeSystem) ) {
            self::$operativeSystem = new OperativeSystem();
        }
        return self::$operativeSystem;
    }

    private static $homeDirectory = null;

    public static function homeDirectory() : HomeDirectory {
        if ( is_null(self::$homeDirectory) ) {
            self::$homeDirectory = new HomeDirectory();
        }
        return self::$homeDirectory;
    }


    private static $qtDirectory = null;

    public static function qtDirectory() : QtDirectory {
        if ( is_null(self::$qtDirectory) ) {
            self::$qtDirectory = new QtDirectory();
        }
        return self::$qtDirectory;
    }
}