<?php

namespace App\Services;

use App\Controllers\ProductController;
use App\Forms\ProductForm;
use App\Models\Product;
use Phalcon\Http\Request;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Translate\Adapter\NativeArray;

/**
 * Class ProductService
 * @package services
 */
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
     * @return \stdClass
     */
    public function getProductList(int $page, int $limit = ProductController::PAGE_RECORDS): \stdClass
    {
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

        return $productPaginate->getPaginate();
    }

    /**
     * @param int $limit
     *
     * @return int
     */
    public function getAllProductsPages(int $limit = ProductController::PAGE_RECORDS): int
    {
        $productsCount = Product::count();
        $pagesCount = intval(ceil($productsCount / $limit));

        return $pagesCount;
    }

    /**
     * @param Request     $request
     * @param NativeArray $translator
     * @param null        $flashSession
     *
     * @return ProductForm|bool
     */
    public function checkAndAdd(Request $request, NativeArray $translator, $flashSession = null)
    {
        $product = new Product();
        $form = new ProductForm($product);

        try {
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
        }
        catch (\Exception $e) {
            $flashSession->error('Form error');
        }

        return $form;
    }
}
