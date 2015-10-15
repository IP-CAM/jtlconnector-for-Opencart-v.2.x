<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Mapper\Product;

use jtl\Connector\OpenCart\Mapper\BaseMapper;

class ProductSpecialPriceItem extends BaseMapper
{
    protected $pull = [
        'customerGroupId' => 'customer_group_id',
        'productSpecialPriceId' => 'product_special_id',
        'priceNet' => 'price'
    ];

    protected $push = [
        'customer_group_id' => 'customerGroupId',
        'product_special_id' => 'productSpecialPriceId',
        'price' => 'priceNet'
    ];
}