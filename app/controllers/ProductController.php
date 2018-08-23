<?php

class ProductController extends AbstractController
{
    const PAGE_RECORDS = 10;

    public function listAction()
    {
        $page = intval($this->dispatcher->getParam('page'));

        $products = Product::find([
            "limit"  => self::PAGE_RECORDS,
            "offset" => self::PAGE_RECORDS * $page,
            "order"  => "createdAt DESC",
        ]);

        echo (new \Phalcon\Debug\Dump())->variable($products->toArray());
    }

    public function addAction()
    {
//        $product = new Product();
//        $product->setName('asd');
//        $product->setDescription('Lorem ipsum ...');
//        $product->setPrice(12.456);
//        $success = $product->save();
//        if ($success) {
//            echo "OK";
//        } else {
//            echo "Err: ";
//
//            $messages = $product->getMessages();
//
//            foreach ($messages as $message) {
//                echo $message->getMessage(), "<br/>";
//            }
//        }

        $this->view->form = new ProductForm();
    }
}
