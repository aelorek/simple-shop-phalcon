<?php


class ProductController extends AbstractController
{
    const PAGE_RECORDS = 10;

    /**
     * @var \ProductService
     */
    var $productService;

    /**
     * Initialize ProductController
     */
    public function initialize()
    {
        parent::initialize();
        $this->productService = new ProductService(
            new MailService($this->getDI()->get('config')->mail->sender),
            $this->userService
        );
    }

    /**
     * Product list with pagination
     */
    public function listAction()
    {
        $page = intval($this->dispatcher->getParam('page'));

        [
            'products'   => $products,
            'pagesCount' => $pagesCount,
        ] = $this->productService->getProductList($page);

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
        $form = $this->productService->checkAndAdd($this->request, $this->translator, $this->flashSession);

        if ($form === true) {
            return $this->redirectToHome();
        }

        $this->view->form = $form;
        $this->view->pick('product/new');
    }
}
