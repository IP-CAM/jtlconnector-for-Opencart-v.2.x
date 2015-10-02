<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper\Specific
 */

namespace jtl\Connector\OpenCart\Mapper\Specific;

use jtl\Connector\OpenCart\Mapper\I18nBaseMapper;

class SpecificI18n extends I18nBaseMapper
{
    protected $pull = [
        'specificId' => 'filter_group_id',
        'languageISO' => null,
        'name' => 'name'
    ];

    protected $push = [
        'filter_group_id' => 'specificId',
        'name' => 'name'
    ];
}