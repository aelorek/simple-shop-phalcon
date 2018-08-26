<?php

use Phalcon\Mvc\Controller;
use Phalcon\Translate\Adapter\NativeArray;

abstract class AbstractController extends Controller
{
    const TRANSLATION_DIR_FORMAT = 'app/messages/%s.php';

    var $translator;

    var $userService;


    /**
     * Initialize AbstractController
     */
    public function initialize()
    {
        $this->userService = $this->di->getShared(UserService::class);
        $this->translator = $this->getTranslation();
        $this->checkAuth();
    }

    /**
     * @return NativeArray
     */
    private function getTranslation()
    {
        $messages = [];
        $language = 'en';
        if (property_exists($this, 'request') && method_exists($this->request, 'getBestLanguage')) {
            $language = $this->request->getBestLanguage();
        }

        $translationFile = sprintf(self::TRANSLATION_DIR_FORMAT, $language);

        require(file_exists($translationFile)
            ? $translationFile
            : sprintf(self::TRANSLATION_DIR_FORMAT, 'en'));

        return new NativeArray(
            [
                'content' => $messages,
            ]
        );
    }

    private function checkAuth()
    {
        try {
            $this->user = $this->session->get('auth');
            $this->view->auth = !is_null($this->user);
        }
        catch (\Exception $e) {

        }
    }

    protected function getUser(): ?Array
    {
        return $this->userService->getUser();
    }

    protected function redirectToHome()
    {
        return $this->dispatcher->getDI()
            ->get('response')
            ->redirect('/', [
                'controller' => 'index',
                'action'     => 'list',
                'for'        => 'home',
            ]);
    }

    protected function forwardToHome()
    {
        return $this->dispatcher->forward([
            'controller' => 'product',
            'action'     => 'list',
        ]);
    }
}
