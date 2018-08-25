<?php

use Phalcon\Paginator\Adapter\Model as Paginator;

class ProductService extends AbstractService
{
    public function __construct()
    {
        parent::__construct();
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
        $pagesCount = ceil($productsCount / $limit);

        $productsQuery = Product::find([
            "order" => "createdAt DESC",
        ]);
        $productPaginator = new Paginator(
            [
                'data'  => $productsQuery,
                'page'  => $page,
                'limit' => $limit,
            ]
        );

        return [
            'products'   => $productPaginator->getPaginate(),
            'pagesCount' => $pagesCount,
        ];
    }
}
