<?php

namespace jtl\Connector\OpenCart\Tests\Mapper\Product;


use jtl\Connector\Model\Identity;
use jtl\Connector\OpenCart\Mapper\Product\Product2Category;
use jtl\Connector\OpenCart\Tests\Mapper\AbstractMapperTest;

class Product2CategoryTest extends AbstractMapperTest
{
    protected function getMapper()
    {
        return new Product2Category();
    }

    protected function getEndpoint()
    {
        return [
            'product_id' => 25,
            'category_id' => 2
        ];
    }

    protected function getHost()
    {
        $result = new \jtl\Connector\Model\Product2Category();
        $result->setProductId(new Identity("25", 0));
        $result->setCategoryId(new Identity("2", 0));
        return $result;
    }


    protected function getMappedHost()
    {
        $result = new \jtl\Connector\Model\Product2Category();
        $result->setProductId(new Identity("25", 0));
        $result->setCategoryId(new Identity("2", 0));
        return $result;
    }

    protected function getMappedEndpoint()
    {
        return [
            'product_id' => 25,
            'category_id' => 2
        ];
    }
}
