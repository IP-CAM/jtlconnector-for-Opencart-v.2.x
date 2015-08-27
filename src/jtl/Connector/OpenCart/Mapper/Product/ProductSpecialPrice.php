<?php

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
        'considerDateLimit' => null,
        'isActive' => null,
        'items' => 'ProductSpecialPriceItem'
    ];

    protected function considerDateLimit($data)
    {
        return true;
    }

    protected function isActive($data)
    {
        $today = date("Y-m-d H:i:s");
        $start = $data['date_start'];
        $end = $data['date_end'];
        return Date::is_open_time_frame($start, $end) || Date::between($today, $start, $end);
    }


}