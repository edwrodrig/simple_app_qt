<?php
declare(strict_types=1);

namespace edwrodrig\qt_app_builder\variable;

/**
 * Class QtPluginsDirectory
 * Variable that hold the Qt plugins directory that is in the installation directory
 * @package edwrodrig\qt_app_builder\variable
 */
class QtPluginsDirectory extends Variable
{
    public function getVariableName(): string
    {
        return "Qt plugins directory";
    }

    public function find() : bool {
        $qtDirectory = Variables::QtDirectory()->get();
        $qtPluginsDirectory = $qtDirectory . '/gcc_64/plugins';
        if ( !is_dir($qtPluginsDirectory) ) {
            $this->throwNotFound("Install Qt correctly!");
        }
        $this->value = $qtPluginsDirectory;
        $this->printFound();
        return true;
    }
}