<?php

namespace jtl\Connector\OpenCart\Mapper\Product;

use jtl\Connector\OpenCart\Mapper\BaseMapper;

class ProductVarCombination extends BaseMapper
{
    protected $pull = [
        'productId' => 'product_id',
        'productVariationId' => 'product_option_id',
        'productVariationValueId' => 'product_option_value_id'
    ];
}