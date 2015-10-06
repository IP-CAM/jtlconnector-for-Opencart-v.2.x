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
        'isDefault' => 'is_default',
        'i18ns' => 'GlobalData\CustomerGroupI18n'
    ];
}