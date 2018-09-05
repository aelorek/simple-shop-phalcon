<?php

namespace App\Plugins;

use Phalcon\Acl;
use Phalcon\Acl\Role;
use Phalcon\Acl\Adapter\Memory as AclList;
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Acl\Resource;

class SecurityPlugin extends Plugin
{
    const ROLE_USER  = 'User';
    const ROLE_GUEST = 'Guest';

    /**
     * @param Event      $event
     * @param Dispatcher $dispatcher
     *
     * @return bool
     */
    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        $auth = $this->session->get('auth');

        if (!$auth) {
            $role = self::ROLE_GUEST;
        } else {
            $role = self::ROLE_USER;
        }

        $controller = $dispatcher->getControllerName();
        $action = $dispatcher->getActionName();
        $acl = $this->getAcl();

        $allowed = $acl->isAllowed($role, $controller, $action);

        if (!$allowed) {
            $dispatcher->getDI()->get('response')->redirect('/', [
                'controller' => 'index',
                'action'     => 'list',
                'for'        => 'home',
            ]);

            return false;
        }
    }

    /**
     * @return AclList
     */
    private function getAcl(): AclList
    {
        $acl = new AclList();

        $acl->setDefaultAction(
            Acl::DENY
        );

        $roles = [
            new Role(self::ROLE_USER),
            new Role(self::ROLE_GUEST),
        ];

        foreach ($roles as $role) {
            $acl->addRole($role);
        }

        $allResources = [
            'product' => [
                'list' => [
                    self::ROLE_GUEST,
                    self::ROLE_USER,
                ],
                'add'  => [
                    self::ROLE_USER,
                ],
            ],
            'auth'    => [
                'login'  => [
                    self::ROLE_GUEST,
                ],
                'logout' => [
                    self::ROLE_USER,
                ],
            ],
        ];

        foreach ($allResources as $resourceName => $actions) {
            $acl->addResource(
                new Resource($resourceName),
                array_keys($actions)
            );

            foreach ($actions as $action => $roles) {
                foreach ($roles as $role) {
                    $acl->allow(
                        $role,
                        $resourceName,
                        $action
                    );
                }
            }
        }

        return $acl;
    }
}