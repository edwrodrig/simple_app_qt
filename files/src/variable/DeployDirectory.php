<?php
declare(strict_types=1);


namespace edwrodrig\qt_app_builder\variable;

/**
 * Class DeployDirectory
 * Deploy directory is where the app with a functional binary should be.
 * Use the {@see DeployDirectory::set() set method} to prepare the deploy directory, deleting if there is a current one.
 * @package edwrodrig\qt_app_builder\variable
 */
class DeployDirectory extends Variable
{
    /**
     * Get the variable name.
     *
     * This name is used for logs an error messages
     * @return string
     */
    public function getVariableName(): string
    {
        return "Deploy directory";
    }

    public function find() : bool {
        $deployDirectory = Variables::BuildDirectory()->get() . "/deploy";

        if ( !is_dir($deployDirectory) ) {
            $this->throwNotFound("You must deploy your Qt project");
        }
        $this->value = $deployDirectory;
        $this->printFound();

        return true;
    }

    /**
     * Set a deploy directory.
     * If exists then clean with rf command
     * @throws VariableNotFoundException
     */
    public function set() {
        $deployDirectory = Variables::BuildDirectory()->get() . "/deploy";
        if ( is_dir($deployDirectory) ) {
            printf("Old Deploy directory FOUND at [%s]...removing!\n", $deployDirectory);
            passthru(sprintf("rm -rf %s", $deployDirectory));
        }
        printf("Creating deploy directory!\n", $deployDirectory);
        mkdir($deployDirectory);
    }
}