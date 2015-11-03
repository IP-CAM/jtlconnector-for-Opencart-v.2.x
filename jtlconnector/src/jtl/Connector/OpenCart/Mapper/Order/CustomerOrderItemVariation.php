<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Mapper\Order;

use jtl\Connector\OpenCart\Mapper\BaseMapper;

class CustomerOrderItemVariation extends BaseMapper
{
    protected $pull = [
        'id' => 'order_option_id',
        'productVariationId' => 'product_option_id',
        'productVariationValueId' => 'product_option_value_id',
        'productVariationName' => 'name',
        'surcharge' => null,
        'valueName' => null
    ];

    protected function surcharge($data)
    {
        return ($data['price_prefix'] == '+') ? doubleval($data['price']) : 0.0;
    }

    protected function valueName($data)
    {
        return ($data['type'] === 'file') ? $data['filename'] : $data['value'];
    }
}