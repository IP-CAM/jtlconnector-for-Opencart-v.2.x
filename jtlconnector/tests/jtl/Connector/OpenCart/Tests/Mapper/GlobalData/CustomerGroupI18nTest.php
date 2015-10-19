<?php

namespace jtl\Connector\OpenCart\Tests\Mapper\GlobalData;

use jtl\Connector\Model\Identity;
use jtl\Connector\OpenCart\Mapper\GlobalData\CustomerGroupI18n;
use jtl\Connector\OpenCart\Tests\Mapper\AbstractMapperTest;

class CustomerGroupI18nTest extends AbstractMapperTest
{

    protected function getMapper()
    {
        return new CustomerGroupI18n();
    }

    protected function getEndpoint()
    {
        return [
            'customer_group_id' => '1',
            'name' => 'Endkunden',
            'code' => 'de'
        ];
    }

    protected function getMappedHost()
    {
        $result = new \jtl\Connector\Model\CustomerGroupI18n();
        $result->setCustomerGroupId(new Identity("1", 0));
        $result->setName('Endkunden');
        $result->setLanguageISO("ger");
        return $result;
    }

    public function testPush()
    {
    }
}
