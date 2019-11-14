<?php
declare(strict_types=1);

namespace edwrodrig\qt_app_builder\variable;


use Exception;

/**
 * Class VariableNotFoundException
 *
 * Throw this when a {@see Variable variable} is not found for some reason
 * @package edwrodrig\qt_app_builder\variable
 */
class VariableNotFoundException extends Exception
{

    /**
     * @var Variable The not found variable object
     */
    private $variable;

    /**
     * @var string This is the message for the end user of a way to solve the not found problem. For example if a installation dir is not found then reinstall the framework.
     */
    private $recoverMessage;

    /**
     * VariableNotFoundException constructor.
     * @param Variable $variable {@see VariableNotFoundException::$this->variable variable}
     * @param string $recoverMessage {@see VariableNotFoundException::$recoverMessage recover message}
     */
    public function __construct(Variable $variable, string $recoverMessage ) {
        parent::__construct(sprintf("%s not found!", $variable->getVariableName()));
        $this->variable = $variable;
        $this->recoverMessage = $recoverMessage;
    }

    /**
     * Get the {@see VariableNotFoundException::$variable not found variable object}
     * @return Variable
     */
    public function getVariable() : Variable {
        return $this->variable;
    }

    /**
     * Get the {@see VariableNotFoundException::$recoverMessage recover message}
     * @return string
     */
    public function getRecoverMessage() : string {
        return $this->recoverMessage;
    }
}