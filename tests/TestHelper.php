<?php

use Phalcon\Di;
use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;

ini_set('display_errors', 1);
error_reporting(E_ALL);

define('ROOT_PATH', __DIR__);
define('BASE_DIR', dirname(__DIR__));
define('APP_PATH', BASE_DIR . '/app');

define('PATH_LIBRARY', APP_PATH . '/library/');
define('PATH_SERVICES', APP_PATH . '/services/');
define('PATH_RESOURCES', APP_PATH . '/resources/');
define('PATH_CONTROLLERS', APP_PATH . '/controllers/');
define('PATH_PLUGINS', __DIR__ . '/plugins/');
define('PATH_MODELS', APP_PATH . '/models/');
define('PATH_DATABASE', ROOT_PATH . '/../database/');


set_include_path(
    ROOT_PATH . PATH_SEPARATOR . get_include_path()
);

include __DIR__ . '/../vendor/autoload.php';

$loader = new Loader();

$loader->registerDirs(
    [
        ROOT_PATH,
        PATH_SERVICES,
        PATH_MODELS,
        PATH_CONTROLLERS,
        PATH_PLUGINS,
    ]
);

$loader->register();

$di = new FactoryDefault();

Di::reset();

Di::setDefault($di);
