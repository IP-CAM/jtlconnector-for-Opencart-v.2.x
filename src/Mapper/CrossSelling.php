<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Mapper;

class CrossSelling extends BaseMapper
{
    protected $pull = [
        'productId' => 'product_id',
        'items' => 'CrossSellingItem'
    ];
}