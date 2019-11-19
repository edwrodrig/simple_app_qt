<?php
declare(strict_types=1);


namespace edwrodrig\qt_app_builder\process;

/**
 * Class GetSharedDependencies
 * This process retrieve the shared dependencies from the current system and copy it in the same folder than the {@see GetSharedDependencies::$binaryFile binary file}.
 *
 * @package edwrodrig\qt_app_builder\process
 */
class GetSharedDependencies
{
    private $binaryFile;

    /**
     * Run the ldd command yo get the dependencies files
     * @param string $binaryFile
     * @return mixed
     * @throws \Exception
     */
    private function runLdd(string $binaryFile) : array {
        $lddCommand = sprintf("ldd -r %s", $binaryFile);
        printf("Running command [%s]...\n", $lddCommand);
        exec($lddCommand, $output, $return_var);
        if ( $return_var != 0 ) {
            throw new \Exception("Error running ldd command!");
        }
        return $output;
    }

    private function getQtLibrariesFromLddOutput(array $lddOutput) : string {
        foreach ( $lddOutput as $line ) {
            $tokens = explode("=>", $line);
            $final_token = trim($tokens[1] ?? $tokens[0]);
            $tokens = explode(" ", $final_token);
            $lib = $tokens[0] ?? null;
            if ( is_null($lib) ) continue;
            if ( !file_exists($lib) ) continue;
            if ( strpos($lib, "/Qt/") !== FALSE ) {

                printf("\t[%s]\n", $lib);
                copy($lib, $target_dir . DIRECTORY_SEPARATOR . basename($lib));
            }
        }
    }

    /**
     * Set the Qt binary file that you want to get the shared dependencies
     * @param $binaryFile
     * @return $this
     */
    public function setBinaryFile($binaryFile) : self {
        $this->binaryFile = $binaryFile;
        return $this;
    }

    /**
     * Calling the process
     * @throws \Exception
     */
    public function process() {
        $this->runLdd($this->binaryFile);
    }


    public function get() {
        $target_binary = '';
        $qt_dir = '';

        exec(sprintf("ldd -r %s", $target_binary), $output, $return_var);
        foreach ( $output as $line ) {
            $tokens = explode("=>", $line);
            $final_token = trim($tokens[1] ?? $tokens[0]);
            $tokens = explode(" ", $final_token);
            $lib = $tokens[0] ?? null;
            if ( is_null($lib) ) continue;
            if ( !file_exists($lib) ) continue;
            if ( strpos($lib, "/Qt/") !== FALSE ) {

                printf("\t[%s]\n", $lib);
                copy($lib, $target_dir . DIRECTORY_SEPARATOR . basename($lib));
            }
        }

        /**
         * libQtDBus and libQt5XcbQpa  is needed for plugin platforms/libqxcb.so
         * Failed to load platform plugin "xcb". Available platforms are:
         */
        $additional_libs = [
            $qt_dir . "/lib/libQt5DBus.so.5",
            $qt_dir . "/lib/libQt5XcbQpa.so.5"
        ];

        foreach ( $additional_libs as $lib ) {
            printf("\t[%s]\n", $lib);
            copy($lib, $target_dir . DIRECTORY_SEPARATOR . basename($lib));
        }
    }
}