<?php

class AuthController extends AbstractController
{
    var $userService;

    public function initialize()
    {
        $this->userService = $this->di->get(UserService::class);
    }

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

    public function logoutAction()
    {
        $this->userService->logout();

        return $this->redirectToHome();
    }

}
