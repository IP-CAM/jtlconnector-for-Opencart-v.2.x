<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper\Product
 */

namespace jtl\Connector\OpenCart\Mapper\Product;

use jtl\Connector\OpenCart\Mapper\BaseMapper;

class ProductStockLevel extends BaseMapper
{
    protected $pull = [
        'productId' => 'product_id',
        'stockLevel' => 'quantity'
    ];
}