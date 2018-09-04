<?php

use Phalcon\Http\Request;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Translate\Adapter\NativeArray;

class ProductService extends AbstractService
{
    /**
     * @var MailService
     */
    var $mailService;

    /**
     * @var UserService
     */
    var $userService;


    /**
     * ProductService constructor.
     *
     * @param MailService $mailService
     * @param UserService $userService
     */
    public function __construct(MailService $mailService, UserService $userService)
    {
        parent::__construct();
        $this->mailService = $mailService;
        $this->userService = $userService;
    }

    /**
     * @param int $page
     * @param int $limit
     *
     * @return array
     */
    public function getProductList(int $page, int $limit = ProductController::PAGE_RECORDS): Array
    {
        $productsCount = Product::count();
        $pagesCount = intval(ceil($productsCount / $limit));

        $productsQuery = Product::find([
            'order' => 'createdAt DESC, id DESC',
        ]);
        $productPaginate = new Paginator(
            [
                'data'  => $productsQuery,
                'page'  => $page,
                'limit' => $limit,
            ]
        );

        return [
            'products'   => $productPaginate->getPaginate(),
            'pagesCount' => $pagesCount,
        ];
    }

    public function checkAndAdd(Request $request, NativeArray $translator, $flashSession = null)
    {
        $product = new Product();
        $form = new ProductForm($product);

        if ($request->isPost()) {
            if (!$form->isValid($request->getPost())) {
                if (!is_null($flashSession)) {
                    foreach ($form->getMessages() as $message) {
                        $flashSession->error($message->getMessage());
                    }
                }
            } else {
                $product->setCurrency($translator->_('_currency'));
                if ($product->save()) {
                    $this->mailService->sendEmail(
                        $this->userService->getUser()['email'],
                        $translator->_('new-product-created')
                    );

                    return true;
                } else {
                    if (!is_null($flashSession)) {
                        foreach ($product->getMessages() as $message) {
                            $flashSession->error($message);
                        }
                    }
                }
            }
        }

        return $form;
    }
}
