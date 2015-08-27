<?php

namespace jtl\Connector\OpenCart\Mapper;

class ProductVariationValue extends BaseMapper
{
    protected $pull = [
        'id' => 'product_option_value_id',
        'productVariationId' => 'product_option_id',
        'extraWeight' => null,
        'stockLevel' => 'quantity',
        'i18ns' => 'ProductVariationValueI18n'
        //'extra_charges' =>
    ];

    protected function extraWeight($data)
    {
        if ($data['weight_prefix'] == '+') {
            return doubleval($data['weight']);
        }
        return 0.0;
    }
}