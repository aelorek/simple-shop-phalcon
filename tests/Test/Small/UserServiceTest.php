<?php

namespace Test\Small;

use App\Models\User;
use App\Services\UserService;
use Test\AbstractTestCase;

/**
 * Class UserServiceTest
 * @package Test\Small
 */
class UserServiceTest extends AbstractTestCase
{
    const EMAIL    = 'test@no-exists.example';
    const PASSWORD = 'P@$$W0rD';

    var $userService;

    public function initialize()
    {
        $user = new User();
        $user->setEmail(self::EMAIL);
        $user->setPassword(password_hash(self::PASSWORD, PASSWORD_BCRYPT, ['cost' => 12]));
        $this->assertTrue($user->save());


        $session = $this->getDI()->getShared('session');
        $this->userService = new UserService($session);
    }

    public function test_should_test_valid_user()
    {
        $this->assertTrue($this->userService->checkAndLoginUser(self::EMAIL, self::PASSWORD, false));
    }

    public function test_should_test_non_valid_user()
    {
        $this->assertFalse($this->userService->checkAndLoginUser('test@example.com', 'test_password', false));
    }
}
