<?php
declare(strict_types=1);

namespace edwrodrig\qt_app_builder\variable;

class BuildDirectory  extends Variable
{

    private $notCheckedValue;
    /**
     * Get the variable name.
     *
     * This name is used for logs an error messages
     * @return string
     */
    public function getVariableName(): string
    {
        return "Build directory";
    }

    public function find() : bool {
        if ( !is_dir($this->notCheckedValue) ) {
            $this->throwNotFound("Put a valid directory");
        }
        $this->value = $this->notCheckedValue;
        $this->printFound();
        return true;
    }

    public function set(string $value) {
        $this->notCheckedValue = $value;
    }
}