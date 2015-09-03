<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper
 */

namespace jtl\Connector\OpenCart\Mapper\Product;

use jtl\Connector\OpenCart\Mapper\BaseMapper;

class ProductPriceItem extends BaseMapper
{
    protected $pull = [
        'productPriceId' => 'product_id',
        'netPrice' => 'price',
        'quantity' => 'subtract'
    ];
}