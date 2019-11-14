<?php
declare(strict_types=1);

namespace edwrodrig\qt_app_builder\variable;

use Exception;

/**
 * Class Variables
 * This class works in the following way.
 *
 * Just call a {@see Variables::__callStatic() method} with the name of a variable.
 * @package edwrodrig\qt_app_builder\variable
 * @method static OperativeSystem OperativeSystem()
 * @method static QtDirectory QtDirectory()
 * @method static HomeDirectory HomeDirectory()
 */
class Variables
{

    /**
     * @var array This map holds the defined variables.
     * The key are the classname without namespace and the vaule are the objects itself.
     */
    private static $variableMap = [];

    /**
     * This method try to return a variable object if this is defined.
     * Just use the name of an object in the variable namespace and that is a subclass of {@see Variable variable}.
     * This is a lazy method. The first time it is called try to create the variable and then return. The next time uses the already created variables.
     * The variables are stored in a {@see Variables::$variableMap internal map}
     * @param $name
     * @param $arguments
     * @return Variable
     * @throws Exception
     */
    public static function __callStatic($name, $arguments) : Variable
    {
        if ( !isset(self::$variableMap[$name]) ) {
            $classname = '\\edwrodrig\\qt_app_builder\\variable\\' . $name;

            if (
                class_exists($classname) and
                is_subclass_of($classname, '\\edwrodrig\\qt_app_builder\\variable\\Variable')
            ) {
                self::$variableMap[$name] = new $classname;

            } else {
                throw new Exception(sprintf("Variable [%s] NOT IMPLEMENTED", $name));
            }

        }

        return self::$variableMap[$name];
    }
}