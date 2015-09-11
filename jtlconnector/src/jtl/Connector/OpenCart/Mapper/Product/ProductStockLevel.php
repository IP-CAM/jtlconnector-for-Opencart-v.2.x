<?php

namespace jtl\Connector\OpenCart\Mapper\Product;

use jtl\Connector\OpenCart\Mapper\BaseMapper;

class ProductStockLevel extends BaseMapper
{
    protected $pull = [
        'productId' => 'product_id',
        'stockLevel' => 'quantity'
    ];
}