<?php

namespace Test\Small;

use App\Controllers\ProductController;
use App\Models\Product;
use App\Services\MailService;
use App\Services\ProductService;
use App\Services\UserService;
use Test\AbstractTestCase;

/**
 * Class ProductServiceTest
 * @package Test\Small
 */
class ProductServiceTest extends AbstractTestCase
{
    /**
     * @var ProductService
     */
    var $productService;

    public function initialize()
    {
        for ($i = 0; $i < 13; $i++) {
            $p = new Product();
            $p->setName('product-' . $i);
            $p->setDescription('Sample product description');
            $p->setPrice($i + 100);
            $p->setCurrency('Test');
            $p->setCreatedAt(new \DateTime());
            $p->setUpdatedAt(new \DateTime());
            $this->assertTrue($p->save());
        }

        $this->productService = new ProductService(
            $this->getDI()->getShared(MailService::class),
            $this->getDI()->getShared(UserService::class)
        );
    }

    public function test_should_test_all_products_count()
    {
        $products = Product::count();
        $this->assertEquals($products, 13);
    }

    public function test_should_test_product_pagination_page_1()
    {
        $products = $this->productService->getProductList(1);
        $pagesCount = $this->productService->getAllProductsPages();

        $this->assertEquals(2, $pagesCount);
        $this->assertEquals(ProductController::PAGE_RECORDS, count($products->items));
        $this->assertEquals(112, $products->items[0]->price); // last added
    }

    public function test_should_test_product_pagination_page_2()
    {
        $products = $this->productService->getProductList(2);
        $pagesCount = $this->productService->getAllProductsPages();

        $this->assertEquals(2, $pagesCount);
        $this->assertEquals(3, count($products->items));
        $this->assertEquals(100, $products->items[2]->price); // first added
    }
}
