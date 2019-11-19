<?php
declare(strict_types=1);


namespace edwrodrig\qt_app_builder\process;


class GetSharedDependencies
{
    private $binaryFile;

    private function runLdd(string $binaryFile) {
        $lddCommand = sprintf("ldd -r %s", $binaryFile);
        printf("Running command [%s]...\n", $lddCommand);
        exec($lddCommand, $output, $return_var);
        if ( $return_var != 0 ) {
            throw new \Exception("Error running ldd command!");
        }
    }

    public function setBinaryFile($binaryFile) : self {
        $this->binaryFile = $binaryFile;
        return $this;
    }

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