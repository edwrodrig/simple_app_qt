<?php
declare(strict_types=1);

namespace edwrodrig\qt_app_builder\variable;

use edwrodrig\qt_app_builder\process\GetSharedDependencies;
use edwrodrig\qt_app_builder\process\LaunchScript;

/**
 * Class QtDeploy
 * Variable that hold the Qt deploy directory
 * @package edwrodrig\qt_app_builder\variable
 */
class QtDeploy extends Variable
{

    public function getVariableName(): string
    {
        return "QtDeploy";
    }

    public function find() : bool {
        if ( Variables::OperativeSystem()->get() === 'linux' ) {
            printf("Linux do not have a deploy executable, using custom implementation!!\n");
            return "";
        } else {
            $this->throwNotFound("NOT IMPLEMENTED FOR THIS OPERATIVE SYSTEM");
            return false;
        }
    }

    /**
     * Call qt deployer
     * In mac and windows you should call winqtdeploy or macqtdeploy. In linux is a custom script
     * @return bool
     * @throws VariableNotFoundException
     * @throws \Exception
     */
    public function call() : bool
    {
        if (Variables::OperativeSystem()->get() === 'linux') {
            return $this->callLinux();
        } else {
            $this->throwNotFound("NOT IMPLEMENTED FOR THIS OPERATIVE SYSTEM");
            return false;
        }

    }

    public function callLinux() {
        printf("Retrieving needed Qt libraries...");
        $getSharedDependencies = new GetSharedDependencies();
        $getSharedDependencies->setBinaryFile(Variables::BinaryDeployFilepath()->get());
        $getSharedDependencies->process();
        $launchScript = new LaunchScript();
        $launchScript->create();
        return true;

    }
}