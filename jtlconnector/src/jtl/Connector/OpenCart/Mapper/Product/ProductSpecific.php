<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper\Product
 */

namespace jtl\Connector\OpenCart\Mapper\Product;

use jtl\Connector\OpenCart\Mapper\BaseMapper;

class ProductSpecific extends BaseMapper
{
    protected $pull = [
        'id' => 'id',
        'productId' => 'product_id',
        'specificValueId' => 'filter_id'
    ];
}