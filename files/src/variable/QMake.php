<?php
declare(strict_types=1);

namespace edwrodrig\qt_app_builder\variable;

/**
 * Class QMake
 * Variable that hold the qmake application absolute path
 * @package edwrodrig\qt_app_builder\variable
 */
class QMake extends Variable
{

    public function getVariableName(): string
    {
        return "QMake";
    }

    public function find() : bool {
        $os = Variables::OperativeSystem()->get();
        $qtDirectory = Variables::QtDirectory()->get();
        $qmake = null;
        if ( $os === 'linux' ) $qmake = $qtDirectory .  "/gcc_64/bin/qmake";
        else if ( $os === 'windows nt') $qmake = $qtDirectory .  "/mingw73_64/bin/qmake.exe";
         else {
            $this->throwNotFound("NOT IMPLEMENTED FOR THIS OPERATIVE SYSTEM");
            return false;
        }

        if ( !file_exists($qmake) )
            $this->throwNotFound(sprintf("You must check if qmake is available in Qt bin directory [%s]", $qmake));

        exec($qmake . " -v", $output, $return);
        if ( $return != 0 )
            $this->throwNotFound(sprintf("Qmake does not work [%s]", $qmake));

        $this->value = $qmake;
        $this->printFound();
        return true;


    }

    /**
     * Call qmake with a .pro file
     * @param string $qmakeProjectFile the .pro file
     * @return bool
     * @throws VariableNotFoundException
     * @throws \Exception
     */
    public function call(string $qmakeProjectFile) : bool {
        $compilationDirectory = Variables::CompilationDirectory()->get();
        printf("Change directory to [%s]\n", $compilationDirectory);
        chdir($compilationDirectory);
        $qmake = Variables::Qmake()->get();
        printf("Calling Qmake...\n");
        $command = sprintf(
            "%s -config release \"CONFIG-=debug_and_release_folder\" %s",
            $qmake,
            $qmakeProjectFile
        );

        printf("Command to execute [%s]\n", $command);
        passthru($command, $return_var);

        if ( $return_var != 0 ) {
            throw new \Exception(sprintf("Error generation Makefile qith QMake\n"));
        }
        return true;

    }
}
