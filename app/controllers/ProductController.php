<?php


class ProductController extends AbstractController
{
    const PAGE_RECORDS = 10;

    /**
     * Product list with pagination
     */
    public function listAction()
    {
        $page = intval($this->dispatcher->getParam('page', 'int'));

        $productService = new ProductService();
        [
            'products'   => $products,
            'pagesCount' => $pagesCount,
        ] = $productService->getProductList($page);

        $this->view->products = $products;
        $this->view->t = $this->translator;
        $this->view->pagesCount = $pagesCount === 0 ? 1 : $pagesCount;
        $this->view->currentPage = $page;
        $this->view->pick('product/list');
    }

    /**
     * Add new product
     * @Auth
     */
    public function addAction()
    {
        $product = new Product();
        $form = new ProductForm($product);

        if ($this->request->isPost()) {
            if (!$form->isValid($this->request->getPost())) {
                foreach ($form->getMessages() as $message) {
                    $this->flashSession->error($message->getMessage());
                }
            } else {
                if ($product->save()) {
                    return $this->dispatcher->forward([
                        'controller' => 'product',
                        'action'     => 'list',
                    ]);
                } else {
                    foreach ($product->getMessages() as $message) {
                        $this->flashSession->error($message);
                    }
                }
            }
        }

        $this->view->form = $form;
        $this->view->pick('product/new');
    }
}
