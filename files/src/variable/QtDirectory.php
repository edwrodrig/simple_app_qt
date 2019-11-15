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
        if ( Variables::OperativeSystem()->get() === 'linux' ) {
            $homeDirectory = Variables::HomeDirectory()->get();
            $this->value = $homeDirectory . "/Qt/5.13.0";
            $this->printFound();
            return true;
        } else {
            $this->throwNotFound("You must install Qt in a available way");
            return false;
        }
    }
}