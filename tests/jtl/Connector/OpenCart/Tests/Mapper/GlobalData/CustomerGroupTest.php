<?php

namespace jtl\Connector\OpenCart\Tests\Mapper\GlobalData;

use jtl\Connector\OpenCart\Mapper\GlobalData\CustomerGroup;
use jtl\Connector\OpenCart\Tests\Mapper\AbstractMapper;

class CustomerGroupTest extends AbstractMapper
{
    protected function getMapper()
    {
        return new CustomerGroup();
    }

    protected function getHost()
    {
        return [
            'id' => 1,
            'isDefault' => true,
            'i18ns' => []
        ];
    }

    protected function getEndpoint()
    {
        return [
            'customer_group_id' => '1',
            'sort_order' => 1,
            'CustomerGroupI18n' => []
        ];
    }

    protected function assertToHost($result)
    {
        var_dump($result);
        $this->assertEquals($this->host['id'], $result->getId()->getEndpoint());
        $this->assertEquals($this->host['isDefault'], $result->getFactor());
        $this->assertEmpty($result->getName());
        // Default values
        $this->assertFalse($result->getIsDefault());
        $this->assertEmpty($result->getDelimiterThousand());
        $this->assertEmpty($result->getDelimiterCent());
    }
}
