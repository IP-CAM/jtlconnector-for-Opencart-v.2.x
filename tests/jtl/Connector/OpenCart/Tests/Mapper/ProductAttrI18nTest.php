<?php

namespace jtl\Connector\OpenCart\Tests\Mapper;

use jtl\Connector\OpenCart\Mapper\ProductAttrI18n;

class ProductAttrI18nTest extends AbstractMapper
{
    protected function getMapper()
    {
        return new ProductAttrI18n();
    }

    protected function getHost()
    {
        return [
            'attribute_id' => 25,
            'code' => 'de',
            'name' => 'Color',
            'text' => 'red'
        ];
    }

    protected function getEndpoint()
    {
        return [
            'productAttrId' => '25',
            'languageISO' => 'de',
            'name' => 'Color',
            'value' => 'red'
        ];
    }

    protected function assertToHost($result)
    {
        // TODO: Implement assertToHost() method.
    }
}
