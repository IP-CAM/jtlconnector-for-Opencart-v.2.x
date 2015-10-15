<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Mapper\Product;

use jtl\Connector\OpenCart\Mapper\BaseMapper;

class ProductPriceItem extends BaseMapper
{
    protected $pull = [
        'productPriceId' => 'product_id',
        'netPrice' => 'price'
    ];
}