<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper\Product
 */

namespace jtl\Connector\OpenCart\Mapper\Product;

use jtl\Connector\OpenCart\Mapper\BaseMapper;

class ProductVariationValueExtraCharge extends BaseMapper
{
    protected $pull = [
        'productVariationValueId' => 'product_option_value_id',
        'extraChargeNet' => null
    ];

    protected function extraChargeNet($data)
    {
        if ($data['price_prefix'] == '+') {
            return doubleval($data['price']);
        }
        return 0.0;
    }
}