<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper
 */

namespace jtl\Connector\OpenCart\Mapper;

class CustomerGroupI18n extends I18nBaseMapper
{
    protected $pull = [
        'customerGroupId' => 'customer_group_id',
        'languageISO' => null,
        'name' => 'name'
    ];
}