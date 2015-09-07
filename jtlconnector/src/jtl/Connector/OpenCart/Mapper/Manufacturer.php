<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper
 */

namespace jtl\Connector\OpenCart\Mapper;

class Manufacturer extends BaseMapper
{
    protected $pull = [
        'id' => 'manufacturer_id',
        'name' => 'name',
        'sort' => 'sort_order'
    ];

    protected $push = [
        'manufacturer_id' => 'id',
        'name' => 'name',
        'sort_order' => 'sort',
        'manufacturer_store' => null
    ];

    protected function manufacturer_store()
    {
        return [intval(0)];
    }
}