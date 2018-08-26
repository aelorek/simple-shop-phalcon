<?php

namespace Test\Medium;

use Test\AbstractTestCase;

/**
 * Class ProductControllerTest
 * @package Test\Medium
 */
class ProductControllerTest extends AbstractTestCase
{
    public function test_should_test_no_access_to_product_add_page_for_guest()
    {
        $this->dispatch('/admin/new-product');
        $this->assertController('product');
        $this->assertAction('add');
        $this->assertResponseCode(302);
    }
}
