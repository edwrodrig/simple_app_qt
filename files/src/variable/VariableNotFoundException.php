<?php
declare(strict_types=1);

namespace edwrodrig\qt_app_builder\variable;


use Exception;

class VariableNotFoundException extends Exception
{

    private $variable;

    private $recoverMessage;

    public function __construct(Variable $variable, string $recoverMessage ) {
        parent::__construct(sprintf("%s not found!", $variable->getVariableName()));
        $this->variable = $variable;
        $this->recoverMessage = $recoverMessage;
    }

    public function getVariable() : Variable {
        return $this->variable;
    }

    public function getRecoverMessage() : string {
        return $this->recoverMessage;
    }
}