<?php

namespace jtl\Connector\OpenCart\Tests\Mapper;

use jtl\Connector\OpenCart\Mapper\Product\ProductAttrI18n;

class ProductAttrI18nTest extends AbstractMapper
{
    protected function getMapper()
    {
        return new ProductAttrI18n();
    }

    protected function getHost()
    {
        return [
            'productAttrId' => '25',
            'languageISO' => 'ger',
            'name' => 'Color',
            'text' => 'red'
        ];
    }

    protected function getEndpoint()
    {
        return [
            'attribute_id' => 25,
            'code' => 'de',
            'name' => 'Color',
            'value' => 'red'
        ];
    }

    protected function assertToHost($result)
    {
        $this->assertEquals($this->host['productAttrId'], $result->getProductAttrId()->getEndpoint());
        $this->assertEquals($this->host['languageISO'], $result->getLanguageISO());
        $this->assertEquals($this->host['name'], $result->getName());
        $this->assertEquals($this->host['text'], $result->getValue());
    }
}
