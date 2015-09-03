<?php

namespace jtl\Connector\OpenCart\Mapper\Product;

use jtl\Connector\OpenCart\Mapper\BaseMapper;

class ProductSpecialPriceItem extends BaseMapper
{
    protected $pull = [
        'customerGroupId' => 'customer_group_id',
        'productSpecialPriceId' => 'product_special_id',
        'priceNet' => 'price'
    ];
}