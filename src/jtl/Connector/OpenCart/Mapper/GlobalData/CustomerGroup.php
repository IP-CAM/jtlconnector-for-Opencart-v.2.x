<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper
 */

namespace jtl\Connector\OpenCart\Mapper\GlobalData;

use jtl\Connector\OpenCart\Mapper\BaseMapper;

class CustomerGroup extends BaseMapper
{
    protected $pull = [
        'id' => 'customer_group_id',
        'isDefault' => null,
        'i18ns' => 'GlobalData\CustomerGroupI18n'
    ];

    protected function isDefault($data)
    {
        return $data['sort_order'] === 1 ? true : false;
    }
}