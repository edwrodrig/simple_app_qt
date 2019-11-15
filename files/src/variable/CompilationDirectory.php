<?php
declare(strict_types=1);


namespace edwrodrig\qt_app_builder\variable;


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
            printf("Compilation directory NOT FOUND!! ...CREATING!\n", $compilationDirectory);
            mkdir($compilationDirectory);
        }
        $this->value = $compilationDirectory;
        $this->printFound();

        return true;
    }

    /**
     * Set a compilation directory.
     * If exists then clean
     * @throws VariableNotFoundException
     */
    public function set() {
        $compilationDirectory = Variables::BuildDirectory()->get() . "/compilation";
        if ( is_dir($compilationDirectory) ) {
            printf("Old Compilation directory FOUND at [%s]...removing!\n", $compilationDirectory);
            passthru(sprintf("rm -rf %s", $compilationDirectory));
        }
    }
}