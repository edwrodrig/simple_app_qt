<?php
declare(strict_types=1);

namespace edwrodrig\qt_app_builder\variable;


abstract class Variable
{
    protected $value = null;

    abstract public function getVariableName() : string;

    public function find() : bool {
        $this->throwNotFound("Found not implemented!");
    }

    public function get() : string {
        if ( $this->value == null ) {
            $this->find();
        }
        return $this->value;

    }

    public function printFound() {
        fprintf(STDOUT,"%s [%s]\n",
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