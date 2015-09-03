<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper
 */

namespace jtl\Connector\OpenCart\Mapper;

class CrossSelling extends BaseMapper
{
    protected $pull = [
        'productId' => 'product_id',
        'items' => 'CrossSellingItem'
    ];

    protected $push = [
        'product_id' => 'productId',
        'CrossSellingItem' => 'items'
    ];
}