<?php
declare(strict_types=1);


namespace edwrodrig\qt_app_builder\variable;

/**
 * Class CompilationDirectory
 * Compilation directory is where the qmake script will do their stuff.
 * Use the {@see CompilationDirectory::set() set method} to prepare the compilation directory, deleting if there is a current one.
 * @package edwrodrig\qt_app_builder\variable
 */
class CompilationDirectory extends Variable
{
    /**
     * Get the variable name.
     *
     * This name is used for logs an error messages
     * @return string
     */
    public function getVariableName(): string
    {
        return "Compilation directory";
    }

    public function find() : bool {
        $compilationDirectory = Variables::BuildDirectory()->get() . "/compilation";

        if ( !is_dir($compilationDirectory) ) {
            $this->throwNotFound("You must compile your Qt project");
        }
        $this->value = $compilationDirectory;
        $this->printFound();

        return true;
    }

    /**
     * Set a compilation directory.
     * If exists then clean with rf command
     * @throws VariableNotFoundException
     */
    public function set() {
        $compilationDirectory = Variables::BuildDirectory()->get() . "/compilation";
        if ( is_dir($compilationDirectory) ) {
            printf("Old Compilation directory FOUND at [%s]...removing!\n", $compilationDirectory);
            passthru(sprintf("rm -rf %s", $compilationDirectory));
        }
        printf("Creating compilation directory!\n", $compilationDirectory);
        mkdir($compilationDirectory);
    }
}