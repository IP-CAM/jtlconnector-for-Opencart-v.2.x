<?php

namespace jtl\Connector\OpenCart\Tests\Mapper\GlobalData;

use jtl\Connector\Model\Identity;
use jtl\Connector\OpenCart\Mapper\GlobalData\ShippingMethod;
use jtl\Connector\OpenCart\Tests\Mapper\AbstractMapperTest;

class ShippingMethodTest extends AbstractMapperTest
{
    protected function getMapper()
    {
        return new ShippingMethod();
    }

    protected function getEndpoint()
    {
        return [
            'id' => '1',
            'name' => 'UPS'
        ];
    }

    protected function getMappedHost()
    {
        $result = new \jtl\Connector\Model\ShippingMethod();
        $result->setId(new Identity("1", 0));
        $result->setName("UPS");
        return $result;
    }

    public function testPush()
    {
    }
}
