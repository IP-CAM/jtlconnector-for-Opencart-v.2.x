<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Mapper\Product;

use jtl\Connector\OpenCart\Mapper\BaseMapper;

class Product2Category extends BaseMapper
{
    protected $pull = [
        'productId' => 'product_id',
        'categoryId' => 'category_id'
    ];

    protected $push = [
        'product_id' => 'productId',
        'category_id' => 'categoryId'
    ];
}