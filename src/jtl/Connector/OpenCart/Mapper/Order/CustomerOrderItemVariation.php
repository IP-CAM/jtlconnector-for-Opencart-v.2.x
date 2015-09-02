<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper\Order
 */

namespace jtl\Connector\OpenCart\Mapper\Order;

use jtl\Connector\OpenCart\Mapper\BaseMapper;

class CustomerOrderItemVariation extends BaseMapper
{
    protected $pull = [
        'id' => 'order_option_id',
        'productVariationId' => 'product_option_id',
        'productVariationValueId' => 'product_option_value_id',
        //'freeField' => '',
        'productVariationName' => 'name',
        'surcharge' => null,
        'valueName' => null
    ];

    protected function valueName($data)
    {
        return ($data['type'] == 'file') ? $data['filename'] : $data['value'];
    }

    protected function surcharge($data)
    {
        if ($data['price_prefix'] == '+') {
            return doubleval($data['price']);
        }
        return 0.0;
    }
}