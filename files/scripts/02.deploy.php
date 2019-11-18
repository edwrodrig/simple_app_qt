<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use edwrodrig\qt_app_builder\variable\VariableNotFoundException;
use edwrodrig\qt_app_builder\variable\Variables;

try {
    Variables::BuildDirectory()->set(__DIR__);
    Variables::BuildDirectory()->find();
    Variables::CompilationDirectory()->find();
    Variables::OperativeSystem()->find();
    Variables::QtDirectory()->find();

    Variables::DeployDirectory()->set();
    Variables::DeployDirectory()->find();

} catch ( VariableNotFoundException $exception ) {
    fprintf(STDERR, "%s [%s]", $exception->getMessage(), $exception->getRecoverMessage());
}