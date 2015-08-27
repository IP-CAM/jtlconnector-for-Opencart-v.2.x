<?php

namespace jtl\Connector\OpenCart\Mapper\Product;

use jtl\Connector\OpenCart\Mapper\BaseMapper;

class ProductAttr extends BaseMapper
{
    protected $pull = [
        'id' => 'attribute_id',
        'productId' => 'product_id',
        'isTranslated' => null,
        'i18ns' => 'ProductAttrI18n'
    ];

    protected function isTranslated()
    {
        return true;
    }
}