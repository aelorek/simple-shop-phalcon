<?php

namespace Test\Small;

use App\Controllers\ProductController;
use Test\AbstractTestCase;

/**
 * Class ConfigurationTest
 * @package Test\Small
 */
class ConfigurationTest extends AbstractTestCase
{

    public function test_should_test_configuration_email()
    {
        $this->assertEquals('fake@example.com', $this->getDI()->get('config')->mail->sender);
    }

    public function test_should_test_pagination_records_count()
    {
        $this->assertEquals(10, ProductController::PAGE_RECORDS);
    }
}
