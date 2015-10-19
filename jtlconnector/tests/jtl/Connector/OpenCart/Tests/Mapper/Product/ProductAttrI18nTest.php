<?php

namespace jtl\Connector\OpenCart\Tests\Mapper\Product;

use jtl\Connector\Model\Identity;
use jtl\Connector\OpenCart\Mapper\Product\ProductAttrI18n;
use jtl\Connector\OpenCart\Tests\Mapper\AbstractMapperTest;

class ProductAttrI18nTest extends AbstractMapperTest
{
    protected function getMapper()
    {
        return new ProductAttrI18n();
    }

    protected function getEndpoint()
    {
        return [
            'attribute_id' => 25,
            'code' => 'de',
            'name' => 'Geschamck',
            'value' => 'Zartbitter'
        ];
    }

    protected function getMappedHost()
    {
        $result = new \jtl\Connector\Model\ProductAttrI18n();
        $result->setProductAttrId(new Identity("25", 0));
        $result->setLanguageISO("ger");
        $result->setName("Geschamck");
        $result->setValue("Zartbitter");
        return $result;
    }

    public function testPush()
    {
    }
}
