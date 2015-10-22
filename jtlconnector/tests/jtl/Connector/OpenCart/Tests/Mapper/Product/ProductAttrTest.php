<?php

namespace jtl\Connector\OpenCart\Tests\Mapper\Product;

use jtl\Connector\Model\Identity;
use jtl\Connector\OpenCart\Mapper\Product\ProductAttr;
use jtl\Connector\OpenCart\Tests\Mapper\AbstractMapperTest;

class ProductAttrTest extends AbstractMapperTest
{
    protected function getMapper()
    {
        return new ProductAttrMock();
    }

    protected function getEndpoint()
    {
        return [
            'attribute_id' => 25,
            'product_id' => 2
        ];
    }

    protected function getMappedHost()
    {
        $result = new \jtl\Connector\Model\ProductAttr();
        $result->setId(new Identity("25", 0));
        $result->setProductId(new Identity("2", 0));
        $result->setIsCustomProperty(false);
        $result->setIsTranslated(true);
        $result->setI18ns([]);
        return $result;
    }

    public function testPush()
    {
    }
}

class ProductAttrMock extends ProductAttr
{
    public function __construct()
    {
        parent::__construct(get_parent_class());
        unset($this->pull['i18ns']);
    }
}