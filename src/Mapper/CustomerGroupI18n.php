<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper
 */

namespace jtl\Connector\OpenCart\Mapper;

use jtl\Connector\Core\Utilities\Language;

class CustomerGroupI18n extends BaseMapper
{
    protected $pull = [
        'customerGroupId' => 'customer_group_id',
        'languageISO' => null,
        'name' => 'name'
    ];

    protected function languageISO($data)
    {
        return Language::convert($data['code']);
    }
}