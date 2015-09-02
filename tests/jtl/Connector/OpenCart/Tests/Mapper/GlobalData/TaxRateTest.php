<?php

namespace jtl\Connector\OpenCart\Tests\Mapper\GlobalData;

use jtl\Connector\OpenCart\Mapper\GlobalData\TaxRate;
use jtl\Connector\OpenCart\Tests\Mapper\AbstractMapper;

class TaxRateTest extends AbstractMapper
{
    protected function getMapper()
    {
        return new TaxRate();
    }

    protected function getHost()
    {
        return [
            'id' => 1,
            'rate' => 19
        ];
    }

    protected function getEndpoint()
    {
        return [
            'tax_rate_id' => '1',
            'rate' => 19
        ];
    }

    protected function assertToHost($result)
    {
        $this->assertEquals($this->host['id'], $result->getId()->getEndpoint());
        $this->assertEquals($this->host['rate'], $result->getRate());
    }
}
