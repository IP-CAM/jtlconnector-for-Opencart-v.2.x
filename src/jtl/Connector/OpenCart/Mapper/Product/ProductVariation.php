<?php

namespace jtl\Connector\OpenCart\Mapper\Product;

use jtl\Connector\OpenCart\Mapper\BaseMapper;

class ProductVariation extends BaseMapper
{
    protected $pull = [
        'id' => 'product_option_id',
        'productId' => 'product_id',
        'i18ns' => 'ProductVariationI18n',
        'values' => 'ProductVariationValue'
    ];
}