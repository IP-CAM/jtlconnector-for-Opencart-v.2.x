<?php

namespace jtl\Connector\OpenCart\Tests\Mapper\GlobalData;

use jtl\Connector\Model\Identity;
use jtl\Connector\OpenCart\Mapper\GlobalData\ShippingMethod;
use jtl\Connector\OpenCart\Tests\Mapper\AbstractMapperTest;

class ShippingMethodTest extends AbstractMapperTest
{
    protected function getMapper()
    {
        return new ShippingMethodMock();
    }

    protected function getEndpoint()
    {
        return [
            'extension_id' => '1',
            'name' => 'UPS'
        ];
    }

    protected function getMappedHost()
    {
        $result = new \jtl\Connector\Model\ShippingMethod();
        $result->setId(new Identity('1', 0));
        $result->setName('UPS');
        return $result;
    }

    public function testPush()
    {
    }
}

class ShippingMethodMock extends ShippingMethod
{
    public function __construct()
    {
        parent::__construct(get_parent_class());
    }

    protected function name(array $data)
    {
        return 'UPS';
    }
}
