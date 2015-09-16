<?php

namespace jtl\Connector\OpenCart\Mapper\Product;

use jtl\Connector\OpenCart\Mapper\I18nBaseMapper;

class ProductVariationI18n extends I18nBaseMapper
{
    protected $pull = [
        'productVariationId' => 'product_option_id',
        'languageISO' => null,
        'name' => 'name'
    ];

    protected $push = [
        'name' => 'name'
    ];
}