<?php
declare(strict_types=1);

namespace edwrodrig\qt_app_builder\variable;

/**
 * Class BinaryDeployFilepath
 * Is the full path of the binary in the compilation directory
 * The directory must be set with the {@see BuildDirectory::set() set method)
 * @package edwrodrig\qt_app_builder\variable
 */
class BinaryDeployFilepath  extends Variable
{
    /**
     * Get the variable name.
     *
     * This name is used for logs an error messages
     * @return string
     */
    public function getVariableName(): string
    {
        return "Binary deploy filepath";
    }

    public function find() : bool {
        $deployDirectory = Variables::DeployDirectory()->get();
        $binaryFilename = Variables::BinaryFilename()->get();
        $binaryDeployFilepath = $deployDirectory . '/' . $binaryFilename;

        if ( !file_exists($binaryDeployFilepath) ) {
            $this->throwNotFound("Check if your binary file is in deploy directory");
        }
        $this->value = $binaryDeployFilepath;
        $this->printFound();
        return true;
    }

    /**
     * Change the mode to executable
     *
     * @throws VariableNotFoundException
     */
    public function changeModeToExecutable() {
        $binaryDeployFilepath = Variables::BinaryDeployFilepath()->get();
        printf("Changing execution mode of [%s]...\n", $binaryDeployFilepath);
        chmod($binaryDeployFilepath , 0755);

    }
}