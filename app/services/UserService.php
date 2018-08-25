<?php

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
    public function checkAndLoginUser(string $email, string $password): bool
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

        $this->session->set(
            self::AUTH_SESSION_KEY,
            [
                'id'    => $user->id,
                'email' => $user->email,
            ]
        );

        return true;
    }
}
