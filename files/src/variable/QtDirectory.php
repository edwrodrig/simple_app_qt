<?php
declare(strict_types=1);

namespace edwrodrig\qt_app_builder\variable;


class QtDirectory extends Variable
{
    public function getVariableName(): string
    {
        return "Qt directory";
    }

    public function find() : bool {
        if ( Variables::operativeSystem()->get() === 'linux' ) {
            $this->value = "/home/edwin/Qt/5.13.0/gcc_64";
            $this->printFound();
            return true;
        } else {
            $this->throwNotFound("You must install Qt in a available way");
            return false;
        }
    }
}