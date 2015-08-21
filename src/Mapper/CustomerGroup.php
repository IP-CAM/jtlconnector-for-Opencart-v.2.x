<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper
 */

namespace jtl\Connector\OpenCart\Mapper;


class CustomerGroup extends BaseMapper
{
    protected $pull = [
        'id' => 'customer_group_id',
        'i18ns' => 'CustomerGroupI18n'
    ];
}