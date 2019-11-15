<?php
declare(strict_types=1);

namespace edwrodrig\qt_app_builder\variable;

/**
 * Class QMake
 * Variable that hold the qmake application absolute path
 * @package edwrodrig\qt_app_builder\variable
 */
class QMake extends Variable
{

    public function getVariableName(): string
    {
        return "QMake";
    }

    public function find() : bool {
        if ( Variables::OperativeSystem()->get() === 'linux' ) {
            $qtDirectory = Variables::QtDirectory()->get();
            $qmake = $qtDirectory .  "/gcc_64/bin/qmake";

            if ( !file_exists($qmake) )
                $this->throwNotFound(sprintf("You must check if qmake is available in Qt bin directory [%s]", $qmake));

            exec($qmake . " -v", $output, $return);
            if ( $return != 0 )
                $this->throwNotFound(sprintf("Qmake does not work [%s]", $qmake));

            $this->value = $qmake;
            $this->printFound();
            return true;

        } else {
            $this->throwNotFound("NOT IMPLEMENTED FOR THIS OPERATIVE SYSTEM");
            return false;
        }
    }
}