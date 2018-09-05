<?php

namespace App\Services;

use App\Models\User;
use Phalcon\Session\Adapter\Files as SessionAdapter;

class UserService extends AbstractService
{
    /**
     * session index for auth
     */
    const AUTH_SESSION_KEY = 'auth';

    /**
     * @var SessionAdapter
     */
    var $session;


    /**
     * UserService constructor.
     *
     * @param SessionAdapter $session
     */
    public function __construct(SessionAdapter $session)
    {
        $this->session = $session;

        parent::__construct();
    }

    /**
     * Logout user - remove session
     */
    public function logout()
    {
        if (!$this->session->has(self::AUTH_SESSION_KEY)) {
            return;
        }
        $this->session->remove(self::AUTH_SESSION_KEY);
    }

    /**
     * Check if user exists in database, and if yes, authorize it
     *
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

    /**
     * Return user basic information if user is logged in
     *
     * @return array|null
     */
    public function getUser(): ?array
    {
        if (!$this->session->has(self::AUTH_SESSION_KEY)) {
            return null;
        }

        return $this->session->get(self::AUTH_SESSION_KEY);
    }
}
