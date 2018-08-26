<?php

namespace Test;

use Phalcon\Di;
use Phalcon\Mvc\View;
use Phalcon\Test\PHPUnit\FunctionalTestCase;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Flash\Session as FlashSession;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use UserService;

abstract class AbstractTestCase extends FunctionalTestCase
{
    /**
     * @var bool
     */
    private $_loaded = false;

    /**
     * Bootstrap configuration
     */
    public function setUp()
    {
        parent::setUp();

        $di = Di::getDefault();

        $di->set('router', function () {
            include getcwd() . '/app/config/router.php';

            return $router;
        });

        $di->setShared('view', function () {
            $config = $this->getConfig();

            $view = new View();
            $view->setDI($this);
            $view->setViewsDir($config->application->viewsDir);

            $view->registerEngines([
                '.volt'  => function ($view) {
                    $config = $this->getConfig();

                    $volt = new VoltEngine($view, $this);

                    $volt->setOptions([
                        'compiledPath'      => $config->application->cacheDir,
                        'compiledSeparator' => '_',
                    ]);

                    return $volt;
                },
                '.phtml' => PhpEngine::class,
            ]);

            return $view;
        });

        $di->setShared('db', function () {
            $config = $this->getConfig();

            $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
            $params = [
                'host'     => $config->database->host,
                'username' => $config->database->username,
                'password' => $config->database->password,
                'dbname'   => $config->database->dbname,
                'charset'  => $config->database->charset,
            ];

            if ($config->database->adapter == 'Postgresql') {
                unset($params['charset']);
            }

            $connection = new $class($params);

            return $connection;
        });

        $di->set(
            'flashSession',
            function () {
                return new FlashSession([
                    'error'   => 'alert alert-danger',
                    'success' => 'alert alert-success',
                    'notice'  => 'alert alert-info',
                    'warning' => 'alert alert-warning',
                ]);
            }
        );

        $di->setShared('session', function () {
            $session = new SessionAdapter();
            $session->start();

            return $session;
        });

        $di->setShared('config', function () {
            return include APP_PATH . '/config/config.test.php';
        });

        $di->setShared(
            \UserService::class,
            function () use ($di) {
                return new \UserService($di->getShared('session'));
            }
        );

        $di->setShared(
            \MailService::class,
            [
                'className' => \MailService::class,
                'arguments' => [
                    [
                            'type'  => 'parameter',
                            'value' => $di->getShared('config')->mail->sender,
                    ],
                ],
            ]
        );

        $this->setDi($di);

        $this->getDi()->getShared('db')->query(file_get_contents(PATH_DATABASE . 'user_structure.sql'));
        $this->getDi()->getShared('db')->query(file_get_contents(PATH_DATABASE . 'product_structure.sql'));

        if (method_exists($this, 'initialize')) {
            $this->initialize();
        }

        $this->_loaded = true;
    }

    /**
     * Check if the test case is setup properly
     *
     * @throws \PHPUnit_Framework_IncompleteTestError;
     */
    public function __destruct()
    {
        if (!$this->_loaded) {
            throw new \PHPUnit_Framework_IncompleteTestError(
                'Please run parent::setUp().'
            );
        }
    }
}