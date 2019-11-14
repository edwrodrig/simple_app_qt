<?php
declare(strict_types=1);

namespace edwrodrig\qt_app_builder\variable;

/**
 * Class OperativeSystem
 * Variable that hold the Qt installation directory
 * @package edwrodrig\qt_app_builder\variable
 */
class QtDirectory extends Variable
{
    public function getVariableName(): string
    {
        return "Qt directory";
    }

    public function find() : bool {
        if ( Variables::operativeSystem()->get() === 'linux' ) {
            $homeDirectory = Variables::homeDirectory()->get();
            $this->value = $homeDirectory . DIRECTORY_SEPARATOR . "Qt/5.13.0";
            $this->printFound();
            return true;
        } else {
            $this->throwNotFound("You must install Qt in a available way");
            return false;
        }
    }
}