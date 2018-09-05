<?php

namespace App\Controllers;

class AuthController extends AbstractController
{
    /**
     * Check if user exists in database with UserService
     *
     * @return string
     */
    public function loginAction()
    {
        if ($this->request->isPost()) {
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            if ($this->userService->checkAndLoginUser($email, $password)) {
                return $this->redirectToHome();
            } else {
                return $this->flashSession->error('Wrong email/password');
            }
        }
    }

    /**
     * Check and logout user with UserService and next redirect to home page (product list)
     *
     * @return mixed
     */
    public function logoutAction()
    {
        $this->userService->logout();

        return $this->redirectToHome();
    }

}
