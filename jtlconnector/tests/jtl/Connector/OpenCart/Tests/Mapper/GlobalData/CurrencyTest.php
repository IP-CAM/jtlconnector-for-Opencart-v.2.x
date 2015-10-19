<?php

namespace jtl\Connector\OpenCart\Tests\Mapper\GlobalData;

use jtl\Connector\Model\Identity;
use jtl\Connector\OpenCart\Mapper\GlobalData\Currency;
use jtl\Connector\OpenCart\Tests\Mapper\AbstractMapperTest;

class CurrencyTest extends AbstractMapperTest
{

    protected function getMapper()
    {
        return new Currency();
    }

    protected function getHost()
    {
        $result = new \jtl\Connector\Model\Currency();
        $result->setId(new Identity("", 1));
        $result->setFactor(0.98);
        $result->setIso('CHF');
        $result->setName('Swiss Francs');
        $result->setNameHtml('CHF');
        $result->setIsDefault(true);
        $result->setDelimiterCent(',');
        $result->setDelimiterThousand('.');
        $result->setHasCurrencySignBeforeValue(false);
        return $result;
    }

    protected function getEndpoint()
    {
        return [
            'currency_id' => '1',
            'value' => 0.98,
            'title' => 'Swiss Francs',
            'code' => 'CHF',
            'symbol_right' => 'CHF',
            'symbol_left' => '',
            'decimal_place' => '2',
            'status' => 1
        ];
    }

    protected function getMappedHost()
    {
        $result = new \jtl\Connector\Model\Currency();
        $result->setId(new Identity("1", 0));
        $result->setFactor(0.98);
        $result->setIso('CHF');
        $result->setName('Swiss Francs');
        $result->setNameHtml('CHF');
        return $result;
    }

    protected function getMappedEndpoint()
    {
        return [
            'currency_id' => '',
            'title' => 'Swiss Francs',
            'value' => 0.98,
            'code' => 'CHF',
            'symbol_left' => '',
            'symbol_right' => 'CHF',
            'decimal_place' => '2',
            'status' => 1
        ];
    }
}
