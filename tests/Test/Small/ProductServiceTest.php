<?php

namespace Test\Small;

use Test\AbstractTestCase;

/**
 * Class ProductServiceTest
 * @package Test\Small
 */
class ProductServiceTest extends AbstractTestCase
{
    var $productService;

    public function initialize()
    {
        for ($i = 0; $i < 13; $i++) {
            $p = new \Product();
            $p->setName('product-' . $i);
            $p->setDescription('Sample product description');
            $p->setPrice($i + 100);
            $p->setCreatedAt(new \DateTime());
            $p->setUpdatedAt(new \DateTime());
            $this->assertTrue($p->save());
        }

        $this->productService = new \ProductService(
            $this->getDI()->getShared(\MailService::class),
            $this->getDI()->getShared(\UserService::class)
        );
    }

    public function test_should_test_all_products_count()
    {
        $products = \Product::count();
        $this->assertEquals($products, 13);
    }

    public function test_should_test_product_pagination_page_1()
    {
        [
            'products'   => $products,
            'pagesCount' => $pagesCount,
        ] = $this->productService->getProductList(1);

        $this->assertEquals(2, $pagesCount);
        $this->assertEquals(\ProductController::PAGE_RECORDS, count($products->items));
    }

    public function test_should_test_product_pagination_page_2()
    {
        [
            'products'   => $products,
            'pagesCount' => $pagesCount,
        ] = $this->productService->getProductList(2);

        $this->assertEquals(2, $pagesCount);
        $this->assertEquals(3, count($products->items));
    }
}
