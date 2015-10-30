<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Mapper\Product;

use jtl\Connector\OpenCart\Mapper\BaseMapper;
use jtl\Connector\OpenCart\Utility\Date;

class ProductSpecialPrice extends BaseMapper
{
    protected $pull = [
        'id' => 'product_special_id',
        'productId' => 'product_id',
        'activeFromDate' => 'date_start',
        'activeUntilDate' => 'date_end',
        'items' => 'Product\ProductSpecialPriceItem',
        'isActive' => null,
        'considerDateLimit' => null
    ];

    protected $push = [
        'product_id' => 'productId',
        'date_start' => 'activeFromDate',
        'date_end' => 'activeUntilDate',
        'priority' => null
    ];

    protected function isActive($data)
    {
        $today = date("Y-m-d H:i:s");
        $start = $data['date_start'];
        $end = $data['date_end'];
        return Date::isOpenTimeFrame($start, $end) || Date::between($today, $start, $end);
    }

    protected function considerDateLimit()
    {
        return true;
    }

    protected function priority()
    {
        return 1;
    }
}