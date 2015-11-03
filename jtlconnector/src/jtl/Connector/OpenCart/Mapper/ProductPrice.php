<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Mapper;

class ProductPrice extends BaseMapper
{
    protected $pull = [
        'productId' => 'product_id',
        'customerGroupId' => 'customer_group_id',
        'items' => 'ProductPriceItem'
    ];

    protected $push = [
        'product_id' => 'productId',
        'customer_group_id' => 'customerGroupId',
        'date_start' => null,
        'date_end' => null
    ];

    protected function date_start()
    {
        return '0000-00-00';
    }

    protected function date_end()
    {
        return '0000-00-00';
    }
}