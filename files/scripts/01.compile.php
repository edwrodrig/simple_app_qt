<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use edwrodrig\qt_app_builder\variable\Variables;

Variables::OperativeSystem()->find();
Variables::QtDirectory()->find();
