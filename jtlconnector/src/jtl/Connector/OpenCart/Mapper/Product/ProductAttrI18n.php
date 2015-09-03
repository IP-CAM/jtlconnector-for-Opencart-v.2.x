<?php

namespace jtl\Connector\OpenCart\Mapper\Product;

use jtl\Connector\OpenCart\Mapper\I18nBaseMapper;

class ProductAttrI18n extends I18nBaseMapper
{
    protected $pull = [
        'productAttrId' => 'attribute_id',
        'languageISO' => null,
        'name' => 'name',
        'value' => 'value'
    ];
}