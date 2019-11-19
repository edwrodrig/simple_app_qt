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
        $os = Variables::OperativeSystem()->get();
        if ( $os === 'linux' ) {
            printf("Linux do not have a deploy executable, using custom implementation!!\n");
            return "";
        } else if ( $os === 'windows nt') {
            $qtDirectory = Variables::QtDirectory()->get();
            $deployQt = $qtDirectory .  "/mingw73_64/bin/windeployqt.exe";


            if ( !file_exists($deployQt) )
                $this->throwNotFound(sprintf("You must check if windeployqt is available in Qt bin directory [%s]", $deployQt));

            exec($deployQt . " -h", $output, $return);
            if ( $return != 0 )
                $this->throwNotFound(sprintf("Windeployqt does not work [%s]", $deployQt));

            $this->value = $deployQt;
            $this->printFound();
            return true;
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
        $os = Variables::OperativeSystem()->get();
        if ( $os === 'linux') {
            return $this->callLinux();
        } else if ( $os === 'windows nt') {
            return $this->callWindowsNt();
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

    public function callWindowsNt() {
        $binaryDeployFilepath = Variables::BinaryDeployFilepath()->get();
        $deployQt = Variables::QtDeploy()->get();

        printf("Calling WinDeployQt...\n");
        $command = sprintf(
            "%s %s",
            $deployQt,
            $binaryDeployFilepath
        );

        printf("Command to execute [%s]\n", $command);
        passthru($command, $return_var);

        if ( $return_var != 0 ) {
            throw new \Exception(sprintf("Error deploying with WinDeployQt\n"));
        }
        return true;

    }
}
