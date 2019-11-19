<?php
declare(strict_types=1);


namespace edwrodrig\qt_app_builder\process;

use edwrodrig\qt_app_builder\util\LddLineParser;
use edwrodrig\qt_app_builder\variable\Variables;
use mysql_xdevapi\Exception;

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
    private function runLdd() : array {
        $lddCommand = sprintf("ldd -r %s", $this->binaryFile);
        printf("Running command [%s]...\n", $lddCommand);
        exec($lddCommand, $output, $return_var);
        if ( $return_var != 0 ) {
            throw new \Exception("Error running ldd command!");
        }
        return $output;
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
     * Always add
     * libQtDBus and libQt5XcbQpa  is needed for plugin platforms/libqxcb.so
     * Failed to load platform plugin "xcb". Available platforms are:
     */
    public function getQtSharedLibraryList() : array {
        $lddOutput = $this->runLdd($this->binaryFile);

        $libraries = [];
        $parser = new LddLineParser();
        foreach ( $lddOutput as $lddOutputLine ) {
            $libraryPath = $parser->parse($lddOutputLine);
            if ( !is_null($libraryPath) )
                $libraries[] = $libraryPath;
        }

        $qtLibDirectory = dirname($libraries[0]);
        $libraries[] = $qtLibDirectory . "/libQt5DBus.so.5";
        $libraries[] = $qtLibDirectory . "/libQt5XcbQpa.so.5";

        return $libraries;
    }

    public function installQtSharedLibraries() {
        $libraries = $this->getQtSharedLibraryList($this->binaryFile);
        $deployDirectory = Variables::DeployDirectory()->get();
        printf("Qt libraries target dir [%s]\n", $deployDirectory);
        foreach ( $libraries as $library) {
            if ( !file_exists($library) )
                throw new \Exception(sprintf("Qt library DOES NOT EXISTS![%s]", $library));

            $libraryBasename = basename($library);
            printf("Installing Qt library [%s]...", $libraryBasename);
            copy($library, $deployDirectory . "/" . $libraryBasename);
            printf("DONE!\n");
        }
    }


    public function installQtPlugins() {
        $plugins = [
                "audio",
                "bearer",
                "iconengines",
                "imageformats",
                "mediaservice",
                "platforms",
                "playlistformats",
                "sqldrivers"
        ];
        $deployDirectory = Variables::DeployDirectory()->get();
        $qtPluginsDirectory = Variables::QtPluginsDirectory()->get();
        printf("Qt plugins target dir [%s]\n", $deployDirectory);
        foreach ( $plugins as $plugin) {
            $pluginSourceDirectory = $qtPluginsDirectory . "/" . $plugin;
            if ( !is_dir($pluginSourceDirectory) ) {
                throw new \Exception("Plugin NOT FOUND!! [%s]". $pluginSourceDirectory);
            }
            printf("Installing Qt plugin [%s]...", $plugin);
            passthru(sprintf(
                "cp -rf %s %s",
                $pluginSourceDirectory,
                $deployDirectory
            ));
            passthru(sprintf(
                "rm -f %s",
                $deployDirectory . "/" . $plugin . "/*.debug"
            ));


            printf("DONE!\n");
        }
    }
    /**
     * Calling the process
     * @throws \Exception
     */
    public function process() {
        $this->installQtSharedLibraries();
        $this->installQtPlugins();

    }


}