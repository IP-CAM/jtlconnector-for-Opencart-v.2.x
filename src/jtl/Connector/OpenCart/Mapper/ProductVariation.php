<?php

namespace jtl\Connector\OpenCart\Mapper;

class ProductVariation extends BaseMapper
{
    protected $pull = [
        'id' => 'product_option_id',
        'productId' => 'product_id',
        'i18ns' => 'ProductVariationI18n',
        'values' => 'ProductVariationValue'
    ];
}