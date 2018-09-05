<?php

namespace App\Controllers;

use App\Services\UserService;
use Phalcon\Mvc\Controller;
use Phalcon\Translate\Adapter\NativeArray;

abstract class AbstractController extends Controller
{
    const TRANSLATION_DIR_FORMAT = 'app/messages/%s.php';

    /**
     * @var NativeArray
     */
    var $translator;

    /**
     * @var UserService
     */
    var $userService;


    /**
     * Initialize AbstractController
     */
    public function initialize()
    {
        $this->userService = $this->getDI()->getShared(UserService::class);
        $this->translator = $this->getTranslation();
        $this->checkAuth();
    }

    /**
     * Get current language and make the translator accessible from controller
     *
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

    /**
     * Assign the information to the view if user is logged in or not
     */
    private function checkAuth()
    {
        try {
            $this->user = $this->getUser();
            $this->view->auth = !is_null($this->user);
        }
        catch (\Exception $e) {

        }
    }

    /**
     * Get current logged in user if exist
     *
     * @return array|null
     */
    protected function getUser(): ?array
    {
        return $this->userService->getUser();
    }

    /**
     * Redirect to home page (product list)
     */
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
}
