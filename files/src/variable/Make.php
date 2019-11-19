<?php
declare(strict_types=1);

namespace edwrodrig\qt_app_builder\variable;

use Exception;

/**
 * Class Make
 *
 * The makefile command executable.
 * @package edwrodrig\qt_app_builder\variable
 */
class Make extends Variable
{

    /**
     * Get the variable name.
     *
     * This name is used for logs an error messages
     * @return string
     */
    public function getVariableName(): string
    {
        return "Makefile";
    }

    public function find() : bool {
        $os =  Variables::OperativeSystem()->get();
        if ( $os === 'linux' ) $make = "make";
	else if ( $os === 'windows nt' ) {
        	$make = "c:/Qt/Tools/mingw730_64/bin/mingw32-make.exe";
        } else {
            $this->throwNotFound("NOT IMPLEMENTED FOR THIS OPERATIVE SYSTEM");
            return false;
        }

        exec($make . " -v", $output, $return);
        if ( $return != 0 )
            $this->throwNotFound(sprintf("Make does not work [%s]", $make));

        $this->value = $make;
        $this->printFound();
        return true;
    }

    /**
     * Calling Make
     * You do not need to pass variables
     * @return bool
     * @throws VariableNotFoundException
     */
    public function call() {
        $compilationDirectory = Variables::CompilationDirectory()->get();
        printf("Change directory to [%s]\n", $compilationDirectory);
        chdir($compilationDirectory);
        $make = Variables::Make()->get();
        printf("Calling Makefile...\n");
        $command = $make;

        printf("Command to execute [%s]\n", $command);
        passthru($command, $return_var);

        if ( $return_var != 0 ) {
            throw new Exception(sprintf("Error compiling with make\n"));
        }
        return true;
    }
}
