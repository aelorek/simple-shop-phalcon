<?php

use Phalcon\Mvc\Controller;
use Phalcon\Translate\Adapter\NativeArray;

abstract class AbstractController extends Controller
{
    const TRANSLATION_DIR_FORMAT = 'app/messages/%s.php';

    var $translator;

    /**
     * Initialize AbstractController
     */
    public function initialize()
    {
        $this->translator = $this->getTranslation();

        $auth = $this->session->get('auth');
        $this->view->auth = !is_null($auth);
    }

    /**
     * @return NativeArray
     */
    private function getTranslation()
    {
        $language = $this->request->getBestLanguage();
        $messages = [];

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

    protected function redirectToHome()
    {
        return $this->dispatcher->forward([
            'controller' => 'product',
            'action'     => 'list',
        ]);
    }
}
