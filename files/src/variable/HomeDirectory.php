<?php
declare(strict_types=1);


namespace edwrodrig\qt_app_builder\variable;

/**
 * Class HomeDirectory
 * Variable that holds Home directory path of the environment variables
 * @package edwrodrig\qt_app_builder\variable
 */
class HomeDirectory  extends Variable
{

    /**
     * Get the variable name.
     *
     * This name is used for logs an error messages
     * @return string
     */
    public function getVariableName(): string
    {
        return "Home directory environment variable";
    }

    public function find() : bool {
        $home = getenv('HOME');
        if ( empty($home) ) {
            $this->throwNotFound("You must define home directory environment variable");
        }
        $this->value = $home;
        $this->printFound();
        return true;
    }
}