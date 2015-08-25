<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper
 */

namespace jtl\Connector\OpenCart\Mapper;

class Product2Category extends BaseMapper
{
    protected $pull = [
        'productId' => 'product_id',
        'categoryId' => 'category_id'
    ];
}