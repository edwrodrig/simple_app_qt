<?php
declare(strict_types=1);

namespace edwrodrig\qt_app_builder\variable;

/**
 * Class Variable
 *
 * This holds a variable for the build system.
 * You need to {@see Variable::find() find} if the variable is available and to {@see Variable::get() get} to use it.
 * Example of variables.
 * - Operative Systems.
 * - Names
 * - Paths
 * @package edwrodrig\qt_app_builder\variable
 */
abstract class Variable
{
    protected $value = null;

    /**
     * Get the variable name.
     *
     * This name is used for logs an error messages
     * @return string
     */
    abstract public function getVariableName() : string;

    /**
     * Find the variable.
     * @return bool true on success
     * @throws VariableNotFoundException if the variable is not found
     */
    public function find() : bool {
        $this->throwNotFound("Found not implemented!");
    }

    /**
     * Just a version of {@see Variable::find() find} that doesn't not throw.
     * This print a message to STDOUT when the variable is not found.
     * @return bool
     */
    public function tryToFind() : bool {
        try {
            $this->find();
            return true;

        } catch ( \Throwable $exception ) {
            fprintf(STDOUT,"%s NOT FOUND!\n",
                $this->getVariableName()
            );
        }
        return false;
    }

    /**
     * Get the variable.
     *
     * It automatically try to {@see Variable::find() find} if it is not found
     * @return string the variable itself
     * @throws VariableNotFoundException if the variable is not found
     */
    public function get() : string {
        if ( $this->value == null ) {
            $this->find();
        }
        return $this->value;

    }

    public function printFound() {
        fprintf(STDOUT,"%s FOUND [%s]\n",
            $this->getVariableName(),
            $this->value
        );
    }

    public function throwNotFound(string $recoverMessage) {
        throw new VariableNotFoundException(
            $this,
            $recoverMessage
        );
    }
}