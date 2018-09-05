<?php

namespace App\Services;

use App\Models\User;
use Phalcon\Session\Adapter\Files as SessionAdapter;

class UserService extends AbstractService
{
    const AUTH_SESSION_KEY = 'auth';

    var $session;

    public function __construct(SessionAdapter $session)
    {
        $this->session = $session;

        parent::__construct();
    }

    public function logout()
    {
        if (!$this->session->has(self::AUTH_SESSION_KEY)) {
            return;
        }
        $this->session->remove(self::AUTH_SESSION_KEY);
    }

    /**
     * @param string $email
     * @param string $password
     *
     * @return bool
     */
    public function checkAndLoginUser(string $email, string $password, $authUser = true): bool
    {
        $user = User::findFirst(
            [
                "email = :email:",
                'bind' => [
                    'email' => $email,
                ],
            ]
        );

        if (!$user || !password_verify($password, $user->password)) {
            return false;
        }

        if (!$authUser) {
            return true;
        }

        $this->session->set(
            self::AUTH_SESSION_KEY,
            [
                'id'    => $user->id,
                'email' => $user->email,
            ]
        );

        return true;
    }

    public function getUser(): ?Array
    {
        if (!$this->session->has(self::AUTH_SESSION_KEY)) {
            return null;
        }

        return $this->session->get(self::AUTH_SESSION_KEY);
    }
}
