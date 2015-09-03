<?php

namespace jtl\Connector\OpenCart\Mapper\Product;

use jtl\Connector\OpenCart\Mapper\I18nBaseMapper;

class ProductVariationValueI18n extends I18nBaseMapper
{
    protected $pull = [
        'productVariationValueId' => 'product_option_value_id',
        'languageISO' => null,
        'name' => 'name'
    ];
}