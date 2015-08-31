<?php

namespace jtl\Connector\OpenCart\Tests\Mapper\GlobalData;

use jtl\Connector\OpenCart\Mapper\GlobalData\Currency;
use jtl\Connector\OpenCart\Tests\Mapper\AbstractMapper;

class CurrencyTest extends AbstractMapper
{

    protected function getMapper()
    {
        return new Currency();
    }

    protected function getHost()
    {
        return [
            'id' => 1,
            'factor' => 0.98,
            'name' => 'Swiss Francs',
            'iso' => 'CHF',
            'hasCurrencySignBeforeValue' => false
        ];
    }

    protected function getEndpoint()
    {
        return [
            'currency_id' => '1',
            'value' => 0.98,
            'title' => 'Swiss Francs',
            'code' => 'CHF',
            'hasCurrencySignBeforeValue' => false
        ];
    }

    protected function assertToHost($result)
    {
        $this->assertEquals($this->host['id'], $result->getId()->getEndpoint());
        $this->assertEquals($this->host['factor'], $result->getFactor());
        $this->assertEquals($this->host['name'], $result->getName());
        $this->assertEquals($this->host['iso'], $result->getISO());
        $this->assertEquals($this->host['hasCurrencySignBeforeValue'], $result->getHasCurrencySignBeforeValue());
        // Default values
        $this->assertFalse($result->getIsDefault());
        $this->assertEmpty($result->getNameHTML());
        $this->assertEmpty($result->getDelimiterThousand());
        $this->assertEmpty($result->getDelimiterCent());
    }
}
