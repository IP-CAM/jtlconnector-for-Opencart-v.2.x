<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper\Product
 */

namespace jtl\Connector\OpenCart\Mapper\Product;

use jtl\Connector\OpenCart\Mapper\BaseMapper;

class ProductPrice extends BaseMapper
{
    protected $pull = [
        'productId' => 'product_id',
        'items' => 'Product\ProductPriceItem'
    ];
}