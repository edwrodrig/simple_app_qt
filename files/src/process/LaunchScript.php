<?php
declare(strict_types=1);


namespace edwrodrig\qt_app_builder\process;


use edwrodrig\qt_app_builder\variable\Variables;

class LaunchScript
{
    public function scriptContent($binaryName) : string {
        $content = <<<'EOF'
#!/bin/sh
appname=%%APPNAME%%

dirname=`dirname $0`
tmp="${dirname#?}"

if [ "${dirname%$tmp}" != "/" ]; then
dirname=$PWD/$dirname
fi
LD_LIBRARY_PATH=$dirname
export LD_LIBRARY_PATH
$dirname/$appname "$@"
EOF;
        return str_replace("%%APPNAME%%", $binaryName, $content);
    }

    public function create() {
        $deployDirectory = Variables::DeployDirectory()->get();
        $binaryFilename = Variables::BinaryFilename()->get();
        $scriptFilename = Variables::LaunchScriptFilename()->get();
        $scriptFilepath = $deployDirectory . "/" . $scriptFilename;
        printf("Creating launch script [%s]...", $scriptFilepath);
        file_put_contents($scriptFilepath, $this->scriptContent($binaryFilename));
        chmod($scriptFilepath, 0755);
        printf("DONE!\n");
    }
}