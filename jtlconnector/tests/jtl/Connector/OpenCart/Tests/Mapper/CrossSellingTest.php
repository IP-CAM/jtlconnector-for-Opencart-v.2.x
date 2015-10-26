<?php

namespace jtl\Connector\OpenCart\Tests\Mapper;

use jtl\Connector\Model\Identity;
use jtl\Connector\OpenCart\Mapper\CrossSelling;

class CrossSellingTest extends AbstractMapperTest
{
    function getMapper()
    {
        return new CrossSellingMock();
    }

    function getEndpoint()
    {
        return [
            'id' => '1',
            'product_id' => '1'
        ];
    }

    protected function getMappedHost()
    {
        $return = new \jtl\Connector\Model\CrossSelling();
        $return->setId(new Identity("1", 0));
        $return->setProductId(new Identity("1", 0));
        $return->setItems([]);
        return $return;
    }

    public function testPush()
    {
    }
}

class CrossSellingMock extends CrossSelling
{
    public function __construct()
    {
        parent::__construct(get_parent_class());
        unset($this->pull['items']);
        unset($this->push['CrossSellingItem']);
    }
}
