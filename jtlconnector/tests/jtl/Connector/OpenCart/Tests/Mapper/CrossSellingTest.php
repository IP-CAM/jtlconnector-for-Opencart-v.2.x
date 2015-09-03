<?php
/**
 * Created by PhpStorm.
 * User: Sven
 * Date: 25.08.2015
 * Time: 14:10
 */

namespace jtl\Connector\OpenCart\Tests\Mapper;

use jtl\Connector\OpenCart\Mapper\CrossSelling;

class CrossSellingTest extends AbstractMapper
{
    function getMapper()
    {
        return new CrossSelling();
    }

    function getHost()
    {
        return [
            'productId' => 1,
            'items' => []
        ];
    }

    function getEndpoint()
    {
        return [
            'product_id' => "1",
            'CrossSellingItem' => []
        ];
    }

    protected function assertToHost($result)
    {
        $this->assertEquals($this->host['productId'], $result->getProductId()->getEndpoint());
        $this->assertEmpty($result->getItems());
    }
}
