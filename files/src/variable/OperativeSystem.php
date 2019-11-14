<?php
declare(strict_types=1);

namespace edwrodrig\qt_app_builder\variable;

/**
 * Class OperativeSystem
 * Variable that hold the Operative system
 * @package edwrodrig\qt_app_builder\variable
 */
class OperativeSystem extends Variable
{

    public function getVariableName(): string
    {
        return "Operative system";
    }

    public function find() : bool {
        $this->value = strtolower(php_uname('s'));
        $this->printFound();
        return true;
    }
}