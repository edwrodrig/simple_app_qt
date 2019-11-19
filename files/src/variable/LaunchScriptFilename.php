<?php
declare(strict_types=1);

namespace edwrodrig\qt_app_builder\variable;

/**
 * Class LaunchScriptFilename
 * The name of the launch script. The launch script is a linux script that set the library path
 * As a standar is run_name_app.sh
 * @package edwrodrig\qt_app_builder\variable
 */
class LaunchScriptFilename extends Variable
{
    /**
     * Get the variable name.
     *
     * This name is used for logs an error messages
     * @return string
     */
    public function getVariableName(): string
    {
        return "Launch script filename";
    }

    public function find() : bool {
        $binaryFilename = Variables::BinaryFilename()->get();
        $this->value = "run_" . $binaryFilename . ".sh";
        $this->printFound();
        return true;
    }
}