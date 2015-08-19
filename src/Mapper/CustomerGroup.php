<?php
/**
 * Created by PhpStorm.
 * User: Sven
 * Date: 19.08.2015
 * Time: 12:24
 */

namespace jtl\Connector\OpenCart\Mapper;


class CustomerGroup extends BaseMapper
{
    protected $pull = [
        'id' => 'customer_group_id',
        'isDefault' => 'boolean',
        'i18ns' => 'CustomerGroupI18n'
    ];
}