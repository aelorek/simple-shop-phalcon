<?php

$loader = new \Phalcon\Loader();


/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    [
        $config->application->controllersDir,
        $config->application->modelsDir,
        $config->application->formsDir,
        $config->application->servicesDir,
        $config->application->pluginsDir,
    ]
)->register();

$loader->registerNamespaces(
    [
        'App\\Models'      => $config->application->modelsDir,
        'App\\Services'    => $config->application->servicesDir,
        'App\\Controllers' => $config->application->controllersDir,
        'App\\Forms'       => $config->application->formsDir,
        'App\\Plugins'       => $config->application->pluginsDir,
    ]
)->register();

