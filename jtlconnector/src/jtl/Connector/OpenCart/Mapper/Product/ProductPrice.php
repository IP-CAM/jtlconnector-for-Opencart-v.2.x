<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Mapper\Product;

use jtl\Connector\OpenCart\Mapper\BaseMapper;

class ProductPrice extends \jtl\Connector\OpenCart\Mapper\ProductPrice
{
    protected $pull = [
        'id' => 'id',
        'productId' => 'product_id',
        'customerGroupId' => 'customer_group_id',
        'items' => 'Product\ProductPriceItem'
    ];
}