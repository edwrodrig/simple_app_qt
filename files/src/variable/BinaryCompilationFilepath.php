<?php
declare(strict_types=1);

namespace edwrodrig\qt_app_builder\variable;

/**
 * Class BinaryCompilationFilepath
 * Is the full path of the binary in the compilation directory
 * The directory must be set with the {@see BuildDirectory::set() set method)
 * @package edwrodrig\qt_app_builder\variable
 */
class BinaryCompilationFilepath  extends Variable
{
    /**
     * Get the variable name.
     *
     * This name is used for logs an error messages
     * @return string
     */
    public function getVariableName(): string
    {
        return "Binary compilation filepath";
    }

    public function find() : bool {
        $compilationDir = Variables::CompilationDirectory()->get();
        $binaryFilename = Variables::BinaryFilename()->get();
        $binaryCompilationFilepath = $compilationDir . '/app/' . $binaryFilename;

        if ( !file_exists($binaryCompilationFilepath) ) {
            $this->throwNotFound("Check if your compilation is right");
        }
        $this->value = $binaryCompilationFilepath;
        $this->printFound();
        return true;
    }

    /**
     * Copy the compilation binary to Deploy Directory.
     *
     * @throws VariableNotFoundException
     */
    public function copyToDeployDirectory() {
        $source = Variables::BinaryCompilationFilepath()->get();
        $targetDirectory = Variables::DeployDirectory()->get();
        $target = $targetDirectory . "/" . Variables::BinaryFilename()->get();
        copy( $source, $target);
        printf("Copying [%s] to [%s]\n", $source, $target);
    }
}