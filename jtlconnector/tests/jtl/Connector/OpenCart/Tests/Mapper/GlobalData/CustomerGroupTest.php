<?php

namespace jtl\Connector\OpenCart\Tests\Mapper\GlobalData;

use jtl\Connector\Model\Identity;
use jtl\Connector\OpenCart\Mapper\GlobalData\CustomerGroup;
use jtl\Connector\OpenCart\Tests\Mapper\AbstractMapperTest;

class CustomerGroupTest extends AbstractMapperTest
{
    protected function getMapper()
    {
        return new CustomerGroupMock();
    }

    protected function getEndpoint()
    {
        return [
            'customer_group_id' => '1',
            'sort_order' => 1,
            'is_default' => true
        ];
    }

    protected function getMappedHost()
    {
        $result = new \jtl\Connector\Model\CustomerGroup();
        $result->setId(new Identity("1", 0));
        $result->setIsDefault(true);
        $result->setI18ns([]);
        return $result;
    }

    public function testPush()
    {
    }
}

class CustomerGroupMock extends CustomerGroup
{
    public function __construct()
    {
        parent::__construct(get_parent_class());
        unset($this->pull['i18ns']);
    }
}